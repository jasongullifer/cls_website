#!/usr/local/bin/python
#Second shebang for the school server, must be switched

# pireblog_parse.py - 02-27-11 JG

#This script will parse the PIRE blog for supplied tags. Within each
#tag it digs out a list of entries with the title, date, blog link,
#and any image for each entry.

#The final data structure is a dictionary that has keys for each tag
#of interest (supplied by the user). Each tag key contains a list of
#entries as the value. Each entry is a dictionary with the title,
#date, and link on the blog of the entry.

#This will be used to make the news page on the PIRE website (in
#progress). Right now, it makes an SSI file that canbe included by the
#news page. It only makes SSI for "news" blog posts, and more will
#have to be added in the future

#Things to fix: 
#1) There's some unwieldy splitting / stripping on the line that deals
#with the link in the second for loop. It works though. Something like
#perl's substitution might be useful here, but I couldn't find it for
#python.

#2) More unwieldy stripping/splitting when getting the image URL

#3) Character encoding is problem in the output to HTML. Special
#characters aren't HTML special characters, thus there is a problem
#when the page is opene.

import urllib2, re, sys
from xml.dom.minidom import parseString
from datetime import datetime
reload(sys) 
sys.setdefaultencoding( "latin-1" )

entryList = list() #list of parsed entries
xmlEntryList = list() #list of xml translated entries
tagDict = dict() #tags are keys, values are list of entries (which are dicts with field keys)
#xhtmlLink='https://blogs.psu.edu/mt4/mt-search.cgi?tag=%s&Template=feed&IncludeBlogs=34640&limit=%s' #base tag xml page

## Variables to change
tagList=('news','press') #tags you want; all tags will get their own SSI/html file
numEntries = 20 #number of entries to parse. default for the blog is 20. I haven't tried increasing/decreasing

def printEntriesHTML(tag, entries, outFile):
    ''' 
    Takes a list of blog entries and prints a HTML list in the following format:
    
    Date - Title/Blog Link
    Image
      
    HTML encoding is a problem with special characters.
    '''
    file = open(outFile,'w')   
    for entry in entries:
        print '<h3>%s - <a href="%s">%s</a></h3>\n' % (entry['date'].strftime('%B %d, %Y'),entry['link'],entry['title'])
        if entry['image'] != "No Image":
            print '<img src="%s"/>' % entry['image']        
    file.close()


for tag in tagList: #Loop through supplied tags
    #currentLink = xhtmlLink % (tag,numEntries) #insert current tag into link
    currentLink = "https://blogs.psu.edu/mt4/mt-search.cgi?tag=news&Template=feed&IncludeBlogs=34640&limit=10"
    tagDict[tag] = list() #set up a list to be the value of the tag key, so we can use .append() later
    file = urllib2.urlopen(currentLink) #download the xml
    data = file.read() #convert to string
    file.close() #close xml
    dom = parseString(data) #parse the xml
    #Gets a list of the parsed entries
    entryList = dom.getElementsByTagName('entry')
    #For every entry, get the title, link, and publication date.. append it to the list of entries for the tag
    for entry in entryList:
        entryDict={}
        currentTitle = entry.getElementsByTagName('title')[0].toxml()
        currentLink = entry.getElementsByTagName('link')[0].toxml()
        currentDate = entry.getElementsByTagName('published')[0].toxml()
        currentContent = entry.getElementsByTagName('content')[0].toxml()
        title = currentTitle.replace('<title>','').replace('</title>','')
        link = currentLink.replace('<link','').split('href=')[1].split('rel=')[0].strip('" ') #unwieldy? might break?
        date = currentDate.replace('<published>','').replace('</published>','')
        content = currentContent.replace('<content>','').replace('</content>','')
        entryDict['title'] = title
        entryDict['link'] = link
        entryDict['date'] = datetime.strptime(date.split('T')[0],'%Y-%m-%d')
        entryDict['content'] = content
        try: #check if there's an image available
            entryDict['image'] = content.split("<img")[1].split('alt=')[0].split('src=')[1].strip('" ') #also unwieldy
        except:
            entryDict['image'] = "No Image"
        tagDict[tag].append(entryDict)


#Make output SSIs for each tag... character encoding becomes a problem here
for tag in tagList:
    printEntriesHTML(tag, tagDict[tag],tag+'.ssi')
