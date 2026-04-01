/**
 * Lossless / light-lossy optimization for img/** raster + SVG.
 * Only writes when output is smaller than the original (raster) or SVG (svgo).
 */
import { readdir, readFile, writeFile } from 'fs/promises';
import { join, extname } from 'path';
import { optimize as optimizeSvg } from 'svgo';
import sharp from 'sharp';

const IMG_DIR = join(process.cwd(), 'img');
const RASTER = new Set(['.jpg', '.jpeg', '.png', '.webp']);

async function* walk(dir) {
  const entries = await readdir(dir, { withFileTypes: true });
  for (const e of entries) {
    const p = join(dir, e.name);
    if (e.isDirectory()) yield* walk(p);
    else yield p;
  }
}

async function processRaster(absPath) {
  const ext = extname(absPath).toLowerCase();
  const before = await readFile(absPath);
  let pipeline = sharp(before);
  let after;

  if (ext === '.png') {
    after = await pipeline.png({ compressionLevel: 9, effort: 10 }).toBuffer();
  } else if (ext === '.jpg' || ext === '.jpeg') {
    after = await pipeline.jpeg({ mozjpeg: true, quality: 82 }).toBuffer();
  } else if (ext === '.webp') {
    after = await pipeline.webp({ quality: 80 }).toBuffer();
  } else {
    return;
  }

  if (after.length < before.length) {
    await writeFile(absPath, after);
    console.log('img', absPath.replace(process.cwd() + '/', ''), `${before.length} → ${after.length} B`);
  }
}

async function processSvg(absPath) {
  const before = await readFile(absPath, 'utf8');
  const { data } = optimizeSvg(before, { path: absPath, multipass: true });
  if (data && data.length < before.length) {
    await writeFile(absPath, data, 'utf8');
    console.log('svg', absPath.replace(process.cwd() + '/', ''), `${before.length} → ${data.length} B`);
  }
}

/** WebP sibling next to .jpg/.png for `<picture>` (overwrites). */
async function emitWebpSibling(absPath) {
  const raw = await readFile(absPath);
  const webpPath = absPath.replace(/\.(jpe?g|png)$/i, '.webp');
  const webpBuf = await sharp(raw).webp({ quality: 82 }).toBuffer();
  await writeFile(webpPath, webpBuf);
  console.log('webp', webpPath.replace(process.cwd() + '/', ''), webpBuf.length + ' B');
}

async function main() {
  try {
    await readdir(IMG_DIR);
  } catch {
    console.log('no img/ directory, skipping');
    return;
  }

  for await (const file of walk(IMG_DIR)) {
    const ext = extname(file).toLowerCase();
    if (['.ico', '.gif'].includes(ext)) continue;
    if (ext === '.svg') await processSvg(file);
    else if (RASTER.has(ext)) {
      await processRaster(file);
      if (ext === '.jpg' || ext === '.jpeg' || ext === '.png') await emitWebpSibling(file);
    }
  }
}

main().catch((err) => {
  console.error(err);
  process.exit(1);
});
