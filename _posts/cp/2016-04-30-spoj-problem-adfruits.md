---
title: 'SPOJ Problem ADFRUITS'
author: 'Rabiul Awal'
subtitle: 'এই প্রবলেমটি একটি স্ট্রেইট ফরোয়ার্ড Shortest Common Subsequence প্রবলেম। ডিরেক্ট Shortest Common Subsequence Algorithm প্রয়োগ করে কিংবা Longest Common Subsequence কে কিছুটা মডিফিকেশন করে এটি সলভ করা সম্ভব।'
layout: post
category:
    - computation
tag:
    - 'competitive-programming'
---
[Advanced Fruits](http://www.spoj.com/problems/ADFRUITS/)
----------------------------------------------------------------

এই প্রবলেমটি একটি স্ট্রেইট ফরোয়ার্ড Shortest Common Subsequence প্রবলেম। ডিরেক্ট Shortest Common Subsequence Algorithm প্রয়োগ করে কিংবা Longest Common Subsequence কে কিছুটা মডিফিকেশন করে এটি সলভ করা সম্ভব। ডাইনামিক প্রোগ্রামিংয়ের খুবই জনপ্রিয় এবং ক্লাসিক্যাল দুটি এলগরিদম হলো LCS ও SCS Algorithm । LCS বা SCS Algorithm কিভাবে কাজ করে তুমি যদি না জেনে থাকো তাহলে নিচের লিঙ্ক থেকে শিখে নিতে পার । খুব সহজ ভাষায় LCS হলো দুটি স্ট্রিংয়ের মধ্যে সবচে longest subsequece টি. দুটি স্ট্রিংয়ের lcs একটির বেশীও হতে পারে। যেমন – eye, eyes স্ট্রিং দুটির longest subsequence হলো eye. উইকিতে Shortest Common Subsequence এর বর্ণনা করা হয়েছে এভাবে – *a shortest common supersequence of strings x and y is a shortest string z such that both x and y are subsequences of z.* যেমন – স্ট্রিং ananas ও banana এর SCS হলো bananas। লক্ষ করো ডেসক্রিপশন অনুযায়ী bananas এর subsequence স্ট্রিং হলো ananas, banana । আবার ananas ও banana এর lcs হলো anana. রেজাল্টিং lcs এর সাথে যেসব ক্যারেক্টার lcs এর অংশ না, সেগুলো জুড়ে দিলেই scs পেয়ে যাবে । anana-র সাথে char b ও s যোগ করে দেখো; কাঙ্ক্ষিত Shortest Common Subsequence – *bananas* পেয়ে যাবে।

এবার মূল সমস্যায় ফিরে আসা যাক। এই সমস্যাটি সমাধান করার জন্য প্রথমে স্ট্রিং দুটির LCS বের করতে হবে । তারপর স্ট্রিং দুটিকে মার্জ করে নিলেই SCS পাওয়া যাবে। খেয়াল রাখতে হবে, যেসব ক্যারেক্টার ম্যাচ করেছে মানে LCS এর ক্যারেক্টারগুলো যেন আউটপুটে একবারের বেশী না আসে।

### সলভিং এপ্রোচ স্টেপ বাই স্টেপ  
১। স্ট্রিং দুটির `LCS` বের করতে হবে।  
২। একটা ক্যারেক্টার টাইপ ভেক্টর ডিক্লেয়ার করে নাও। এই ভেক্টরের মাঝে যে ক্যারেক্টারগুলো স্ট্রিং দুটিতে ম্যাচ করে অর্থাৎ যেসব ক্যারেক্টার `LCS` এর পার্ট সেগুলোকে জমা রাখবো।  
৩। ম্যাচিং ক্যারেক্টারগুলোকে খুঁজে বের করার জন্য ব্যকট্র্যাক চালাও। এবং করেস্পন্ডিং ক্যারেক্টারগুলো ক্যারেক্টার টাইপ ভেক্টরটিতে পুশ করো।  
৪। ক্যারেক্টার ভেক্টরে লুপ ঘুরাও এবং দুটি স্ট্রিং ট্রাভার্স করে রেজাল্টেন্ট ক্যারেক্টারগুলো প্রিন্ট দাও।

### হাতে-কলমে ম্যানিপুলেশন</span> 
apple peach  
Here, s1 = apple, and s2 = peach. LCS will be 2 here. And corresponding characters are ‘p’ and ‘e’, as shown. ap**p**l**e** and **pe**ach. So, we will get two character at ***char vector***. Thus, the two given string will have below value.
```c
s1Arr = false | false | true | false | true  
s2Arr = true | true | false | false | false
```


<script src="https://gist.github.com/rabiulcste/052320262b72e060ea9581586f387129.js"></script>  

### LCS Learning লিঙ্ক  

2. [https://www.youtube.com/watch?v=NnD96abizww&amp;list=PLrmLmBdmIlpsHaNTPP\_jHHDx\_os9ItYXr&amp;index=27](https://www.youtube.com/watch?v=NnD96abizww&list=PLrmLmBdmIlpsHaNTPP_jHHDx_os9ItYXr&index=27)  
3. <http://www.geeksforgeeks.org/dynamic-programming-set-4-longest-common-subsequence/>