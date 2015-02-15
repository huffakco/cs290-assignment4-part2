/* blatantly copied from: */
/* jwolford OSU CS290 videos */
/* https://developer.mozilla.org/en-US/docs/Traversing_an_HTML_table_with_JavaScript_and_DOM_Interfaces */
/* https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started */
/* includes code from OSU CS290 Canvas Discussion */

function VideoObject(params) {
  this.id = params.id;
  this.name = params.name;
  this.category = params.category;
  this.length = params.length;
  this.rented = params.rented;
}

/* use to store array of Video Objects indexed by id*/
function VideoObjList() {
  this.list = new Array();
  this.rtype;

  this.deleteVideoObjFromList = function(id) {
    for (var i = id; i < this.list.length; i++)
    {
      this.list[i] = this.list[i + 1];
    }
    this.list.pop();
  };
}

var convertVideosToList = function(req, obj) {
    /* define HTTP response as a JavaScript object */
    var testJSON = JSON.parse(req.responseText);
    console.log(testJSON);
    /* search for html_url, description,  */
    /* language under files.file name object.language */
    /* if description is empty set to "generic gist" */
    for (var i = 0; i < testJSON.length; i++)
    {
      /*Reference: */
 /* https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/for...in */
      for (var prop in testJSON[i]) {
        if (prop === 'name')
        {
          var name = testJSON[i].name;
        }
        if (prop === 'category')
        {
          var category = testJSON[i].category;
        }
        if (prop === 'id')
        {
          var id = testJSON[i].id;
        }
        if (prop === 'length')
        {
          var length = testJSON[i].length;
        }
        if (prop === 'rented')
        {
          var rented = testJSON[i].rented;
        }
      }
      if (!(name))
      {
          var name = 'unknown';
      }
      if (!(id))
      {
          var id = '12';
      }
      if (!(category))
      {
          var category = 'unknown';
      }
      if (!(length))
      {
          var length = '0';
      }
      if (!(rented))
      {
          var rented = 'FALSE';
      }

      var newVideoObj = new VideoObject({ id: id,
                                name: name,
                                category: category,
                                length: length,
                                rented: rented});

 
      obj.list.push(newVideoObj);
    }

    /* Generate the table with results */
    deleteTable(obj);
    generate_table(obj);
};



/* Create the objects to keep track of this page */
var videoList = new VideoObjList;


/* Generate a table around VideoObjList */
/* Reference: */
/* https://developer.mozilla.org/en-US/docs/Traversing_an_HTML_table_with_JavaScript_and_DOM_Interfaces */
function generate_table(arr) {
  // creates a <table> element and a <tbody> element
  var elementId = document.getElementById(arr.listId);
  var tbl = document.createElement('table');
  tbl.id = [arr.listId + 'Table'];
  tblID = [arr.listName + 'Table'];
  var tblHeader = document.createElement('thead');
  var tblBody = document.createElement('tbody');

  // Define header row
  var row = document.createElement('tr');

  // Define header elements
  var tblHead1 = document.createElement('th');
  tblHead1.innerHTML = arr.listStr;
  row.appendChild(tblHead1);

  var tblHead2 = document.createElement('th');
  tblHead2.innerHTML = 'Language';
  row.appendChild(tblHead2);

  var tblHead3 = document.createElement('th');
  tblHead3.innerHTML = 'Description';
  row.appendChild(tblHead3);
  tblHeader.appendChild(row);

  tblHeader.appendChild(row);

  // creating all cells
  for (var i = 0; i < arr.list.length; i++) {
    // creates a table row
    var row = document.createElement('tr');
     // Create a <td> element and a text node, make the text
      // node the contents of the <td>, and put the <td> at
      // the end of the table row
    var cell1 = document.createElement('td');
    var cellChkBox = document.createElement('input');
    cellChkBox.type = 'checkbox';
    cellChkBox.id = [arr.listName + i];
    cellChkBox.checked = false;
    var cell2 = document.createElement('td');
    var cellName = document.createTextNode(arr.list[i].name);
    var cell3 = document.createElement('td');
    var listLink = document.createElement('a');
    listLink.id = [arr.listName + 'lnk_' + i];
    listLink.href = arr.list[i].url;
    listLink.innerText = arr.list[i].description;
    cell1.appendChild(cellChkBox);
    cell2.appendChild(cellName);
    cell3.appendChild(listLink);
    row.appendChild(cell1);
    row.appendChild(cell2);
    row.appendChild(cell3);

    // add the row to the end of the table body
    tblBody.appendChild(row);
  }

  // put the <tbody> in the <table>
  tbl.appendChild(tblHeader);
  tbl.appendChild(tblBody);
   // appends <table> into <body>
  elementId.appendChild(tbl);
  // sets the border attribute of tbl to 2;
  //tbl.setAttribute("border", "2");

  // add event listener to checkbox and link
  for (var i = 0; i < arr.list.length; i++) {
    var cellChkBoxId = document.getElementById([arr.listName + i]);
    if (arr.listId === 'searchResults') {
        cellChkBoxId.onclick = handleChkBoxSearch;
    } else {
        cellChkBoxId.onclick = handleChkBoxFavorites;
    }
    var linkClickId = document.getElementById([arr.listName + 'lnk_' + i]);
    linkClickId.ondblclick = linkClickId.href;
  }
}

function deleteTable(obj) {
  elementId = document.getElementById(obj.listId);
  var tableId = document.getElementById([obj.listId + 'Table']);
  if (tableId)
  {
    for (var i = 0; i < tableId.rows.length; i++) {
        tableId.deleteRow(i);
    }
    tableId.deleteTHead();
    elementId.innerHTML = '';
  }
  elementId.innerHTML = '';
}

getCheckedListItem = function(objList) {
  var idx = -1;
  for (var i = 0; i < objList.list.length; i++)
  {
    var id = document.getElementById([objList.listName + i]);
    if (id.checked)
    {
      idx = i;
    }
  }
  return (idx);
};

/* Handle updating the Search and moving to favorites */
handleChkBoxSearch = function() {
  /* Find the checked item */
  var idx = getCheckedListItem(gist);
  if (idx >= 0)
  {
    /* Add item to favorites (indexed in gist object) */
    var tmpObj = new HtmlObject({
        name: gist.list[idx].name,
        description: gist.list[idx].description,
        url: gist.list[idx].url,
        gistId: gist.list[idx].gistId});
    favor.list.push(tmpObj);

    /* Resave favorites */
    saveLocalSearch();

    /* Remove item from gist list */
    gist.removeHtmlObjFromList(idx);

    /* Remove old favorites table */
    deleteTable(favor);

    /* Update favorites table */
    generate_table(favor);

    /* Update search table */
    deleteTable(gist);
    generate_table(gist);
  }
};

/* Handle removing from the favorites */
handleChkBoxFavorites = function() {
  /* Find the checked item */
  /* Find the checked item */
  var idx = getCheckedListItem(favor);
  if (idx >= 0)
  {
    /* Remove item from favorites */
    favor.removeHtmlObjFromList(idx);

    /* Resave favorites */
    saveLocalSearch();

    /* Update favorites table */
    deleteTable(favor);
    generate_table(favor);
  }
};

window.onload = function() {
  sendRequest("'getVideoList':'Get All'");
  console.log("sent Request");
};




$urlStr = 'videoLibrary.php';


//References:
// http://stackoverflow.com/questions/9713058/sending-post-data-with-a-xmlhttprequest
// http://www.openjs.com/articles/ajax_xmlhttp_using_post.php
function sendRequest(params) {  // params is a stringify'd set of key values
  var http = new XMLHttpRequest();
  var url = "videoLibrary.php";

  http.open("GET", url+"?"+params, true);
  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
      alert(http.responseText);
    }
  }
  http.send(null);
}

 
// var url = "videoLibrary.php";
// var params = "lorem=ipsum&name=binny";
// http.open("POST", url, true);

// //Send the proper header information along with the request
// http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
// http.setRequestHeader("Content-length", params.length);
// http.setRequestHeader("Connection", "close");

// http.onreadystatechange = function() {//Call a function when the state changes.
    // if(http.readyState == 4 && http.status == 200) {
        // alert(http.responseText);
    // }
// }
// http.send(params);



/* Object to manage the list of pages */
function VideoList() {
  this.reqPages = new Array;

  this.loadGistPages = function() {
    /* empty previous results - new request */
    this.reqPages.length = 0;
    gist.list.length = 0; /* empty list of previous results */

    /* search requests for each page */
    searchParams.page = document.getElementsByName('page_input')[0].value;
    for (var i = 0; i < searchParams.page; i++)
    {
      var nextReq = new GistPage(i + 1);
      this.reqPages.push(nextReq);
      console.log(this.reqPages[i].httpRequest);
    }
  };
}

/* Object to manage a request for a specific Gist page */
 function videoRequest(pageNum) {
   this.httpRequest = new XMLHttpRequest();
   this.page = pageNum;

   if (!this.httpRequest) {
      throw 'unable to create HttpRequest.';
    }
    // Copied from Nickolas Jurczak on Canvas Discussion
    // Setup the URL to make the request
    this.baseurl = 'https://api.github.com/gists/public';
    this.url = this.baseurl + '?page=' + pageNum + '&per_page=30';

    /* Reference: */
    /* https://developer.mozilla.org/en-US/docs/AJAX/Getting_Started */
    /* function that will handle processing the response */
    this.httpRequest.onreadystatechange = function() {
      /* function to handle the request response */
              /* check the response code */

      if ((!(this) || (this === 'null')))
      {
          console.log('onreadystatechange called but undefined or null');
      }
      else {
        if (this.status === 200) {

          if (this.readyState === 4) {
              // everything is good, the response is received
              //console.log(this.responseText);

              /* Add to list of HtmlObjects */
              convertGistPageToList(this, gist);
          } else {
            console.log('onreadystatechange called but not ready');
          }
        }
        else
        {
            console.log('onreadystatechange called but not status = 200');
        }
      }
    };

    // make and send the request
    this.httpRequest.open('GET', this.url);
    // Example URL calls from assignment input:
    //httpRequest.open('GET','http://api.github.com/gists/public');
    //  httpRequest.open('GET','https://developer.github.com/v3/gists/#list-gists');
    this.httpRequest.send();
}
