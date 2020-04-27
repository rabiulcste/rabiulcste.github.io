---
title: 'Indian 500 cities Data Exploration'
date: '2017-10-11T15:41:57+06:00'
status: publish
permalink: /indian-500-cities-data-exploration
author: 'Rabiul Awal'
excerpt: ''
type: post
id: 910
category:
    - computation
tag: []
post_format: []
swp_open_graph_image_data:
    - 'false'
swp_open_graph_image_url:
    - ''
swp_cache_timestamp:
    - '438652'
---
### Dataset Info

[Arijit Mukherjee](https://www.kaggle.com/zed9941)<time datetime="2016-12-21T10:11:07.167Z" title="2016-12-21T10:11:07.167Z"><span class="dataset-header-v2__meta-bullet"> created t</span></time>his dataset merging ***the census 2011 of Indian Cities with Population more than 1 Lac*** and ***City wise number of Graduates from the Census 2011***, to create a visualization of where the future cities of India stand today. I collected it from *Kaggle* and tried some data visualization technique to explore Indian 500 cities.

#### [](https://github.com/rabiulcste/Kaggle-Kernels-ML/tree/master/Top%20500%20Indian%20Cities%20Data#attributes-info)Attributes Info

- ‘name\_of\_city’ : Name of the City
- ‘state\_code’ : State Code of the City
- ‘state\_name’ : State Name of the City
- ‘dist\_code’ : District Code where the city belongs ( 99 means multiple district )
- ‘population\_total’ : Total Population
- ‘population\_male’ : Male Population
- ‘population\_female’ : Female Population
- ‘0-6\_population\_total’ : 0-6 Age Total Population
- ‘0-6\_population\_male’ : 0-6 Age Male Population
- ‘0-6\_population\_female’ : 0-6 Age Female Population
- ‘literates\_total’ : Total Literates
- ‘literates\_male’ : Male Literates
- ‘literates\_female’ : Female Literates
- ‘sex\_ratio’ : Sex Ratio
- ‘child\_sex\_ratio’ : Sex ratio in 0-6
- ‘effective\_literacy\_rate\_total’ : Literacy rate over Age 7
- ‘effective\_literacy\_rate\_male’ : Male Literacy rate over Age 7
- ‘effective\_literacy\_rate\_female’: Female Literacy rate over Age 7
- ‘location’ : Lat,Lng
- ‘total\_graduates’ : Total Number of Graduates
- ‘male\_graduates’ : Male Graduates
- ‘female\_graduates’ : Female Graduates

### [](https://github.com/rabiulcste/Kaggle-Kernels-ML/tree/master/Top%20500%20Indian%20Cities%20Data#what-you-may-learn-here)What you may learn here?

This notebook is a detailed investigation on top 500 Indian cities. I worked from two side. One approach is statewise analysis and another one citywise. Findings was quite interesting!

### [](https://github.com/rabiulcste/Kaggle-Kernels-ML/tree/master/Top%20500%20Indian%20Cities%20Data#dependencies)Dependencies

This project requires Python 3.5 and the following Python libraries installed:

- [SciPy](http://www.scipy.org/)
- [Scikit Learn](http://scikit-learn.org/)
- [Numpy](http://www.numpy.org/)
- [Matplotlib](https://matplotlib.org/)
- [Seaborn](http://seaborn.pydata.org/)

You will also need to have software installed to run and execute a [Jupyter Notebook](http://jupyter.org/).

Install [Anaconda](https://www.continuum.io/downloads), a pre-packaged Python distribution that contains all of the necessary libraries and software for this project.

### [](https://github.com/rabiulcste/Kaggle-Kernels-ML/tree/master/Top%20500%20Indian%20Cities%20Data#visualization-question-answered)Visualization: Question Answered

- Which are the top 10 highly populated cities of the country?
- What states listed top by number of cities in 500?
- What are the most populated states of India?
- Show total population of the country in cities map
- What are the most male populated states?
- What are the most female populated states?
- What are top 10 literate cities of India?
- Find effective literates across states.
- Analyzing graduates across states.
- What are top 50 cities by population in India?
- Show total graduate percentage in entire population of top 50 cities?
- What are the top 15 cities where female gradutes lives?
- Show difference in average number of male and female graduates?
- Show difference in average number of male and female literates?
- Analyzing Sex ratio across states.
- Male vs female literates comparison.
- What are the facts of top states?
- Is there any linear relation between sex ration and graduate ratio?
- What are the top 5 undeveloped states of India?
- What are the top 10 undeveloped cities of India?

### 500 cities data exploration notebook

<span style="text-decoration: underline;">click on the image below to view full DATA EXPLORATION Jupyter notebook .</span>

### [![](../../uploads/2017/10/image.png)](https://nbviewer.jupyter.org/gist/rabiulcste/918643f8a911dc910d71c1e60f36e1e2)