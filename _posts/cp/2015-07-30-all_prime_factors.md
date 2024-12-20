---
title: 'পূর্ণ সংখ্যার প্রাইম ফ্যাক্টরাইজেশন'
author: Rabiul Awal
subtitle: 'নাম্বার থিওরীতে কোন একটি ধনাত্মক সংখ্যার মৌলিক গুণনীয়ক বা প্রাইম ফ্যাক্টর হলো এমন কতগুলো মৌলিক সংখ্যা যা ঐ সংখ্যাটিকে সঠিকভাবে ভাগ করে । মানে, যে সকল প্রাইম নাম্বার দিয়ে ঐ সংখ্যাটিকে ভাগ দিলে ভাগশেষ শূন্য হয়, তারাই সংখ্যাটির মৌলিক গুণনীয়ক।'
layout: post
category:
    - computation
tag:
    - 'number-theory'
    - 'competitive-programming'
mathjax: true
---
### প্রাইম ফ্যাক্টরাইজেশন 
নাম্বার থিওরীতে কোন একটি ধনাত্মক সংখ্যার মৌলিক গুণনীয়ক বা **প্রাইম ফ্যাক্টর** হলো এমন কতগুলো মৌলিক সংখ্যা যা ঐ সংখ্যাটিকে সঠিকভাবে ভাগ করে। মানে, যে সকল প্রাইম নাম্বার দিয়ে ঐ সংখ্যাটিকে ভাগ দিলে ভাগশেষ শূন্য হয়, তারাই সংখ্যাটির মৌলিক গুণনীয়ক। মৌলিক গুণনীয়ক নির্ণয়ের এ প্রক্রিয়াকে বলা হয় – **ইন্টিজার ফ্যাক্টরাইজেশন** । ফান্ডামেন্টাল এরিথমেটিক থিওরেম অনুযায়ী প্রত্যেকটি ধনাত্মক সংখ্যার একটি সিঙ্গেল এবং ইউনিক প্রাইম ফ্যাক্টরাইজেশন থাকা আবশ্যিক।

প্রাইম ফ্যাক্টরাইজেশনের উদাহরণ –  
৩৬০ = ২ \* ২ \* ২ \* ৩ \* ৩ \* ৫  
ফ্যাক্টরগুলো পাওয়ার আকারেও দেখানো সম্ভব ।  
৩৬০ = ২^৩ \* ৩^২ \* ৫  
যেসকল ধনাত্মক পূর্ণসংখ্যার কমন প্রাইম ফ্যাক্টর থাকে না তাদেরকে **কো-প্রাইম** বলা হয়। দুটি সংখ্যার গসাগু-র মান যদি ১ হয়, তাদেরকেও কো-প্রাইম বলা হয়।

### এলগরিদম
একটি সংখ্যার(N) প্রাইম ফ্যাক্টরস বের করার ইফিশিয়েন্ট উপায়  
১. যতক্ষণ পর্যন্ত $N$ ২ দিয়ে ভাগ যায়, ততক্ষণ ভাগ করা এবং ২ কে প্রাইম ফ্যাক্টর হিসেবে প্রিন্ট করতে হবে ।  
২. এই ধাপে এসে $N$ অবশ্যই বিজোড় সংখ্যা হবে । এখন একটি লুপ চালাতে হবে । কাউন্টার $i = 3$ থেকে sqrt(N) পর্যন্ত। যতক্ষণ পর্যন্ত $i$ দ্বারা $N$ বিভাজ্য হয়, ততক্ষণ $i$ কে প্রিন্ট করতে হবে এবং $N$ কে $i$ দ্বারা ভাগ করতে হবে । তারপর কাউন্টার মান ২ বৃদ্ধি করতে হবে । এবং লুপ $sqrt(N)$ পর্যন্ত চলতে থাকবে ।  
৩. যদি সংখ্যাটি (N) একটি প্রাইম নাম্বার হয় এবং ২ থেকে বড় হয়, তাহলে উপরের দুই ধাপে N এর মান ১ হবে না। সেক্ষেত্রে $N$ ২ থেকে বড় হলে, $N$ এর মান প্রিন্ট করতে হবে। মানে $N$ নিজেই নিজের প্রাইম ফ্যাক্টর ।

### কোড
এই এলগরিদম বুঝলে প্রাইম নাম্বার নির্ণয়ের সিভ অব এরাটস্থেনিজ এলগরিদম সহজেই বুঝতে পারবে । হুবহু একই কনসেপ্ট। প্রাইম ফ্যাক্টরাইজেশন বিস্তারিত বুঝার জন্য নিচের কোড দেখো।  
<script src="https://gist.github.com/rabiulcste/a2c56ba4be6a75e9555a20398d21f5cb.js"></script>