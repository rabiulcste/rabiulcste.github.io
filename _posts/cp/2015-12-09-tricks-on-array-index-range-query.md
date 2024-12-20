---
title: 'Tricks on array index range query'
author: Rabiul
subtitle: 'ধরা যাক, আমাদের কাছে `ara[5]` সাইজের একটা এরে আছে । আগেই বলে রাখছি, এরে ইনডেক্স শুরু হবে 1 থেকে, 0 থেকে না। আমাদের এই এরেতে ৭ বার কুয়েরি করতে হবে \[x, y\] ইন্টার্ভালে । প্রতি কুয়েরিতে ওই ইন্টার্ভালের ইনডেক্সগুলোতে 2 যোগ করতে হবে । কুয়েরি শেষে পরিবর্তিত `array` প্রিন্ট করতে হবে । ara[5] = {2, 3, 7, 4, 10}; // array input // 07 queries 1 5 2 4 5 5 2 5'
layout: post
category:
    - computation
tag:
    - 'competitive-programming'
mathjax: true
---

### প্রব্লেম
ধরা যাক, আমাদের কাছে `ara[5]` সাইজের একটা এরে আছে। আগেই বলে রাখছি, এরে ইনডেক্স শুরু হবে $1$ থেকে, $0$ থেকে না। আমাদের এই এরেতে ৭ বার কুয়েরি করতে হবে `[x, y]` ইন্টার্ভালে। প্রতি কুয়েরিতে ওই ইন্টার্ভালের ইনডেক্সগুলোতে $2$ যোগ করতে হবে। কুয়েরি শেষে পরিবর্তিত `array` প্রিন্ট করতে হবে।

```cpp
ara[5] = {2, 3, 7, 4, 10}; // array input  
// 07 queries  
1 5  
2 4  
5 5  
2 5  
3 4  
1 3  
3 5
```


Query 1~5 means add $2$ at array index starting from $1$ then $2, 3, 4$ and 5. Now array looks like `ara[5] = {4, 5, 9, 6, 12}`  
Query 2~4 means add $2$ at array index starting from $2$ then $3$ and $4$. Now array increased to `ara[5] = {4, 7, 11, 8, 12}`  
Repeat this process for other queries.

এই ধরনের সমস্যা সাধারনত সেগমেন্ট ট্রি(লেজি প্রপাগেশন) কিংবা বাইনারি ইনডেক্স ট্রি দিয়ে সলভ করা যায়, যার কমপ্লেক্সিটি $O(\log n)$। আমি যেই ট্রিক্সটা নিয়ে লিখছি সেটির কমপ্লেক্সিটি অবশ্য $O(n)$. তবে $2×10^5$ সাইজের ইনপুটের ক্ষেত্রে এটা ভালোভাবে কাজে দিবে আর ইমপ্লিমেন্ট করাও সোজা ।


### সমাধান
মূল কথায় আসি। আমার ট্রিকস হলো কোন ইনডেক্স কতবার কুয়েরি করা হয়েছে সেটা হিসেব করে রাখা। উপরের কুয়েরির জন্য যার ফলাফল আসে –

`ara index[1]` কুয়েরি করা হয়ে $2$ বার  
`ara index[2]` কুয়েরি করা হয়ে $4$ বার  
`ara index[3]` কুয়েরি করা হয়ে $6$ বার  
`ara index[4]` কুয়েরি করা হয়ে $5$ বার  
`ara index[5]` কুয়েরি করা হয়ে $4$ বার

এইটুকু হিসেব করতে পারলেই কাজ প্রায় শেষ । এখন `ara` এর প্রতিটা ইনডেক্সের মানের সাথে ( কুয়েরির মান $X$ যে ভেল্যুটা যোগ করতে বলা হয়ে তার মান ) যোগ করলেই আপডেটেড `ara` পাওয়া যাবে।

আপডেটেড এরে ভেল্যুগুলো এমন হবে –
```python
ara[1] = 2 + 2*2  
ara[2] = 3 + 4*2  
ara[3] = 7 + 6*2  
ara[4] = 4 + 5*2  
ara[5] = 10 + 4*2
```

### কোডিং


```cpp
int i, q, n, x, y, ara[20010], v[20010], fr[20010];

int main()  
{  
    //ara[5] = {2, 3, 7, 4, 10};
    
    scanf(“%d %d”, &amp;amp;n, &amp;amp;q);  
    // input array values  
    for(i = 1; i &amp;lt;= n; i++) {  
        cin&amp;gt;&amp;gt;ara[i]; // 1 indexed  
    }  
    while(q–)  
    {  
        cin &amp;gt;&amp;gt; x &amp;gt;&amp;gt; y;  
        v[x] += 1;  
        v[y+1] -= 1;  
    }  
    for(i = 1; i &amp;lt;= n; i++) {  
        fr[i] = fr[i-1] + v[i];  
        printf(“index %d asks for %d timesn”, i, fr[i]);  
    }

    //Updating new array  
    printf(“nNew array:”);  
    for(i = 1; i &amp;lt;= n; i++) {  
        ara[i] = ara[i] + fr[i]* 2;  
        printf(” %d”, ara[i]);  
    }  
    printf(“n”);  
    return 0;  
}
```

