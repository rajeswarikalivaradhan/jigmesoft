<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->


    <title>Hello, world!</title>
  </head>
  <body>
<div id="content"></div>
    <script>
      function hndlr(response) {
          console.log(response,'res');
          console.log(response.items,'item');
          console.log(response.items.length,'length');
      /*for (var i = 0; i < response.items.length; i++) {

        var item = response.items[i];
        // in production code, item.htmlTitle should have the HTML entities escaped.
        document.getElementById("content").innerHTML += "<br>" + item.htmlTitle;
      }*/
    }
	
	</script>
$file = cURL('https://www.googleapis.com/customsearch/v1?key=' . $key . '&cx=' . $cseNumber . '&q=' . $keyword . '&siteSearchFilter=i&alt=json&start=' . $start,
'https://www.googleapis.com/customsearch/v1?key=' . $key . '&cx=' . $cseNumber . '&q=' . $keyword . '&siteSearchFilter=i&alt=json&start=' . $start, null);
echo $file;
	<script src="https://www.googleapis.com/customsearch/v1?key=AIzaSyA65MkFDAHuG0WMoOAmx0xjL5FIruVD4aA&cx=002056607121710290324:c3fdk6o-nni&q=1usd&callback=hndlr">
    </script>
  </body>
</html>