---
title: 'Multiplication of Two Big Numbers (more than 100 digit)'
author: 'Rabiul Awal'
excerpt: 'First, you should read the code two times without comment. I think, you will understand the algorithm. if not, then follow comments. ------------------------------------- here, we use primary school math logic. let two numbers 1133 333. 1133 333 ---------- 3399 -&gt;3399 ..... res\[0\] = 9'
layout: post
category:
    - computation
tag: ['নাম্বার থিওরী']
mathjax: true
---
### কনসেপ্ট
First, you should read the code two times without comment.  
I think, you will understand the algorithm. if not, then follow comments.  
————————————-  
Here, we use primary school math logic. let two numbers $1133$ and $333$.  
$1133$  
$333$  
———-  
3399 -&gt;3399 ….. res\[0\] = 9

33990 -&gt;3738\[9\] // add 3399 + 33990 = 37389 ….. res\[1\] = 8  
339900 -&gt;3772\[8\]\[9\] // add 37389 + 339900 = 377289 … res\[2\] = 2  
———-  
377289  
on first line we took $3$ and multiply with $1133$ and result is $3399$  
on second line we took $3$ and multiply with $1133$, and we got 33990,  
then we add this $33990$ with $3399$ and result is $3738[9]$.  
on third line, we follow same way.  
you should read code first to know `res[0], res[1], res[2]`...

—————————————


### কোড 
<script src="https://gist.github.com/rabiulcste/c42304946954c9b224d3a3414741d479.js"></script>