This is my projet of landing pages, i have many landing page, have almost the same structure and code, and if i want work in ecommerce for another product i duplicate a codebase and change many thing in the html and also js.

So want i want is create a dashboard admin when i can give me the values changes that i did and images, pixels id, text , content, link, apis, ... etc. 
and this dashbord is hosted in a shared hosted, because i don't have VPS, so i word with some technologies and framwork that i can host its in a shared host.

and the purpose of this dashboard is generate me the codebase automaticlly after give hime all params.


so now take the example of this codebase ( @c:\Users\abder\Documents\lucci-moriny-main\lucci-moriny-main/chemise_simple/ ).
the params that i need to be dynamic are : 
1- The content text in HTML and prices
2- The pixel ID (Tiktok and Facebook)
3- The images in the slider (slideshow-container) by order.
4- The form (! most important):
* the form must be dynamic , the product can have : 
** sample (so i need just the name , adress, phone and offre)
** single variante (color)
** two variantes (color and size)
** and the form can have offre input (or gift) 
5- Images of body (can be local images or url link)
6- témoignage and reviews (each product have a review), but i want the possibility to suggestions of current reviews (because many landing page have same reviews)
7- The file JS that contain the sheetDB API (in the example of @c:\Users\abder\Documents\lucci-moriny-main\lucci-moriny-main/chemise_simple/  is api_cozmo_landing_page.js):
* each product landing page can have his onw format in sheetDBData (but the standard is the format exist in the api_cozmo_landing_page.js of  @c:\Users\abder\Documents\lucci-moriny-main\lucci-moriny-main/chemise_simple/)
*each product landing page can have his SheetDB API custome (fh6013f2bw0nq)