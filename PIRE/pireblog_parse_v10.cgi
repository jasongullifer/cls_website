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
import urllib
import re
import sys
import cgi
#import cgitb
from xml.dom.minidom import parseString
from datetime import datetime
#from optparse import OptionParser

def printEntriesHTML(tag, entries,printStyle,num=''):
    ''' 
    Takes a list of blog entries and prints a HTML list in the following format:
    
    Date - Title/Blog Link
    Image
      
    HTML encoding is a problem with special characters.
    '''
    if num == '':
        num = len(entries)
    
    if printStyle == 'feature':
        for entry in entries[:num]:
            print '<a href="%s" target="new"><h2>%s</h2></a><p><em>%s</em></p>' % (entry['link'],entry['title'],entry['date'].strftime('%B %d, %Y'))
            print '<p>%s</p>' % entry['content']
	    print '<hr>'

    else:
        tagcapped = tag[0].capitalize() + tag[1:]
        print '<a href="https://blogs.psu.edu/mt4/mt-search.cgi?blog_id=34640&tag=%s&limit=20" target="new"><h2>%s</h2></a>' % (tag,tagcapped)
        for entry in entries[:num]:
            if entry['image'] != "No Image":
                thumbnail = '<a href="%s" target="new"><img src="%s" width=15px style="border:0px;"/></a>' % (entry['icon_dest'],entry['image'])
	    else: 
                thumbnail = '<a href="%s" target="new"><img src="./images/blog.png" width=15px style="border:0px;"/></a>' %(entry['link'])
            print '<p style="margin-left:.5in;text-indent:-.5in">%s | <strong>%s</strong> - <a href="%s">%s</a></p>\n' % (thumbnail,entry['date'].strftime('%B %d, %Y'),entry['link'],entry['title'])
	

reload(sys) 
sys.setdefaultencoding( "latin-1" )
print "Content-Type: text/html\n\n"
entryList = list() #list of parsed entries
xmlEntryList = list() #list of xml translated entries
tagDict = dict() #tags are keys, values are list of entries (which are dicts with field keys)
interDict = list()
xhtmlLink='http://cls.psu.edu/pire/blog/index.xml' #base tag xml page

## Variables
# parser = OptionParser()
# parser.add_option("-n", "--numEntries", dest="numEntries", default=20,
#                   help="Number of entries to display")
# parser.add_option("-t", "--tags", dest="tagList", default="news",
#                   help="Tags to display")
# (options, args) = parser.parse_args()

# tagList = options.tagList.split(',')
# numEntries = options.numEntries #number of entries to parse. default for the blog is 20. I haven't tried increasing/decreasing

#### This section retrieves arguments from the HTML call ###########
args = cgi.FieldStorage()

if "printStyle" not in args: 
    printStyle = 'list'
else:
    printStyle = re.sub(r'\W+', '', str(args["printStyle"].value))

if "numEntries" not in args: 
    numEntries = 20
else:
    numEntries = int(args["numEntries"].value)

if "tags" not in args: 
    tagList = "news"
else:
    tagList = args["tags"].value

if "combo" not in args: 
    combo = 'listwise'
else:
    combo = re.sub(r'\W+', '', str(args["combo"].value))

    
tagList = tagList.split(',')

for tag in tagList:
    tagDict[tag] = list()

######################################################################

file = urllib.urlopen(xhtmlLink) #download the xml
data = file.read() #convert to string
file.close() #close xml
dom = parseString(data) #parse the xml
#Gets a list of the parsed entries
entryList = dom.getElementsByTagName('item')

#For every entry, get the title, link, and publication date.. append it to the list of entries for the tag
for entry in entryList:
    entryDict={}
    tags = []
    currentTitle = entry.getElementsByTagName('title')[0].toxml()
    currentLink = entry.getElementsByTagName('link')[0].toxml()
    currentDate = entry.getElementsByTagName('pubDate')[0].toxml()
    currentContent = entry.getElementsByTagName('description')[0].toxml()
    title = currentTitle.replace('<title>','').replace('</title>','')
    link = currentLink.replace('<link>','').replace('</link>','')
    date = currentDate.replace('<pubDate>','').replace('</pubDate>','')
    tagSet = entry.getElementsByTagName('category')
    content = currentContent.replace('<description>','').replace('</description>','').replace(']]>','').split('CDATA[')[1]
    content = content.replace('<div><br /></div>','').replace('</div>','</p>').replace('<div>','<p>')
    for articleTag in tagSet:
        tags.append(articleTag.toxml().replace('<category domain="http://www.sixapart.com/ns/types#tag">','').replace('</category>',''))
    entryDict['title'] = title
    entryDict['link'] = link
    entryDict['date'] = datetime.strptime(re.sub(',','' , re.split('\s[0-9]+:[0-9]+:[0-9]+',date)[0]),'%a %d %b %Y')
    entryDict['content'] = content
    entryDict['tags'] = tags
    try: #check if there's an image available
        #entryDict['image'] = content.split("<img")[1].split('alt=')[0].split('src=')[1].strip('" ') #also unwieldy
        if content.count('<a href=') == 1:
		entryDict['icon_dest'] = content.split('<a href="')[1].split('">')[0]
		if entryDict['icon_dest'].count('.pdf') > 0:
			entryDict['image'] = './images/pdf.png'
		else: entryDict['image'] = './images/link.png'
	else: entryDict['image'] = "No Image"
    except:
        entryDict['image'] = "No Image"

    tagIntersect = list(set(entryDict['tags']) & set(tagList))
    
    for eachTag in tagIntersect:
        tagDict[eachTag].append(entryDict)
    
    if combo == 'intersect':
	    if len(tagIntersect) == len(tagList):
    		interDict.append(entryDict)
    		
    if combo == 'union':
    	if len(tagIntersect) != 0:
	    	interDict.append(entryDict)

if combo == 'listwise':
	for tag in tagList:
		printEntriesHTML(tag, tagDict[tag],printStyle,numEntries)
if combo == 'intersect':
	title = ' '
	printEntriesHTML(title, interDict,printStyle,numEntries)
if combo == 'union':
	title = ' '
	printEntriesHTML(title, interDict,printStyle,numEntries)
