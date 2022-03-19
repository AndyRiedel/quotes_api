# -*- coding: utf-8 -*-

import pandas as pd

df = pd.read_json('quotes.json')
df = df[['Quote', 'Author', 'Category']].drop_duplicates(subset=['Quote'])
df['Author'] = df['Author'].apply(lambda x: x[:x.find(',')] if ',' in x else x)

authors = pd.Series(df['Author'].unique())
categories = pd.Series(df['Category'].unique())

authors = dict(zip(authors.values, (authors.index + 1).astype(str)))
categories = dict(zip(categories.values, (categories.index + 1).astype(str)))

df['authorId'] = df['Author'].apply(lambda x: authors[x])
df['categoryId'] =df['Category'].apply(lambda x: categories[x])

df.rename(columns={'Quote':'quote'}, inplace=True)



with open(r'categories.csv', 'w', encoding='utf-8') as f:
    for line in list(categories.keys()):
        f.write(line + '\n')
        
        
with open(r'authors.csv', 'w', encoding='utf-8') as f:
    for line in list(authors.keys()):
        f.write(line + '\n')
        
        
#thre must be two quotes with author id = 4 and category id = 4
if df[(df['authorId']=='5') & (df['categoryId']=='4')].shape[0] > 2:
    relabel_quotes = list(df[(df['authorId']=='4') & (df['categoryId']=='4')].index)
    for i in range(len(relabel_quotes)):
        if i > 1:
            idx = relabel_quotes[i]
            df.at[idx, 'categoryId'] = '5'
elif df[(df['authorId']=='5') & (df['categoryId']=='4')].shape[0] < 2:
    if df[df['authorId']=='5'].shape[0] == 1: #if author only has one quote, reassign another quote to that author
        for i in range(df.shape[0]):
            if df[df['authorId']==str(i)].shape[0] > 1:
                #reassign one quote from this author to author 5
                idx = df[df['authorId']==str(i)].index[0]
                df.at[idx, 'authorId'] == '5'
                break
    author_indexes = list(df[df['authorId']=='5'].index)
    idx = 0
    while df[(df['authorId']=='5') & (df['categoryId']=='4')].shape[0] < 2:
        df.at[author_indexes[idx], 'categoryId'] = '4'
        idx += 1
        
df[['quote', 'authorId', 'categoryId']].to_csv(r'quotes.csv', header=False, index=False)
