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
function ObjList() {
  this.list = new Array();

  this.deleteObjFromList = function(id) {
    for (var i = id; i < this.list.length; i++)
    {
      this.list[i] = this.list[i + 1];
    }
    this.list.pop();
  };
}

/* Create the objects to keep track of this page */
var videoList = new ObjList; // track videos currently displayed
var reqList = new ObjList; // track requests

var convertVideosToList = function(req) {
    for (var i = 0; i < req.length; i++)
    {
      var testJSON = JSON.parse(req[i]);
      console.log(testJSON);
      var newVideoObj = new VideoObject({ id: testJSON.id,
                                name: testJSON.name,
                                category: testJSON.category,
                                length: testJSON.length,
                                rented: testJSON.rented});

      videoList.list.push(newVideoObj);
    }

    /* Generate the table with results */
    deleteTable(videoList);
    generate_table(videoList);
};

var handleResponse = function(req) {
  var testJSON = JSON.parse(req);
  if (testJSON) {
    console.log(testJSON);  
    for (var prop in testJSON) {
      if (prop === 'result') {
        var result = testJSON.result;
        if (!(result)) {
          errorFunction('Result was false');
          return;
        }
      }
      if (prop === 'categories') {
        var categories = testJSON.categories;
        listCategories(categories);
        return;
      }
      if (prop === 'videos') {
        var videos = testJSON.videos;
        convertVideosToList(videos);
        return;
      }
    }
  }
  
};

var errorFunction = function(str) {
  console.log(str);
};

var listCategories = function(categories) {
  for (var i=0; i < categories.length; i++) {
    console.log(categories[i]); 
  }
}

/* Generate a table around VideoObjList */
/* Reference: */
/* https://developer.mozilla.org/en-US/docs/Traversing_an_HTML_table_with_JavaScript_and_DOM_Interfaces */
function generate_table(arr) {
  // creates a <table> element and a <tbody> element
  var elementId = document.getElementById('videoTable');
  var tbl = document.createElement('table');
  tbl.id = 'videoTableList';

  var tblHeader = document.createElement('thead');
  var row = document.createElement('tr');
  var tblHead0 = document.createElement('th');
  tblHead0.innerHTML = 'Id';
  row.appendChild(tblHead0);
  var tblHead1 = document.createElement('th');
  tblHead1.innerHTML = 'Name';
  row.appendChild(tblHead1);
  var tblHead2 = document.createElement('th');
  tblHead2.innerHTML = 'Category';
  row.appendChild(tblHead2);
  var tblHead3 = document.createElement('th');
  tblHead3.innerHTML = 'Length';
  row.appendChild(tblHead3);
  var tblHead4 = document.createElement('th');
  tblHead4.innerHTML = 'Rented';  
  row.appendChild(tblHead4);
  var tblHead5 = document.createElement('th');
  tblHead5.innerHTML = 'Delete';  
  row.appendChild(tblHead5); 
  tblHeader.appendChild(row);
 
  var tblBody = document.createElement('tbody');

  // creating all cells
  for (var i = 0; i < arr.list.length; i++) {
    // creates a table row
    var row = document.createElement('tr');
    row.id = ['row'+i];
     // Create a <td> element and a text node, make the text
      // node the contents of the <td>, and put the <td> at
      // the end of the table row
    var cell0 = document.createElement('td');
    var cellName0 = document.createTextNode(arr.list[i].id);
    var cell1 = document.createElement('td');
    var cellName1 = document.createTextNode(arr.list[i].name);
    var cell2 = document.createElement('td');
    var cellName2 = document.createTextNode(arr.list[i].category);
    var cell3 = document.createElement('td');
    var cellName3 = document.createTextNode(arr.list[i].length);
    var cell4 = document.createElement('td');
    var cellChkBox = document.createElement('input');

    cellChkBox.type = 'button';
    cellChkBox.id = ['chk'+i];
    cellChkBox.checked = false;
    if (arr.list[i].rented) {
      cellChkBox.value = "Check In";
    }
    else {
      cellChkBox.value = "Check Out";
   }

    var cell5 = document.createElement('td');
    var cellDelete = document.createElement('input');

    cellDelete.type = 'button';
    cellDelete.id = ['del' + i];
    cellDelete.value = "Delete";
   
    cell0.appendChild(cellName0);
    cell1.appendChild(cellName1);
    cell2.appendChild(cellName2);
    cell3.appendChild(cellName3);
    cell4.appendChild(cellChkBox);
    cell5.appendChild(cellDelete);
    
    row.appendChild(cell0);
    row.appendChild(cell1);
    row.appendChild(cell2);
    row.appendChild(cell3);
    row.appendChild(cell4);
    row.appendChild(cell5);

    // add the row to the end of the table body
    tblBody.appendChild(row);
  }

  // put the <tbody> in the <table>
  tbl.appendChild(tblHeader);
  tbl.appendChild(tblBody);
  // appends <table> into <body>
  elementId.appendChild(tbl);

  // add event listener to checkbox and link
  for (var i = 0; i < arr.list.length; i++) {
    var cellChkBoxId = document.getElementById(['chk' + i]);
    var linkClickId = document.getElementById(['del' + i]);
    //rowIdName = ['row'+i];
    //tmpStr = "handleCheckedRow('"+ ['row'+i] + "')";
    cellChkBoxId.onclick = function() {
             handleCheckedRow(this.id);
            };
    linkClickId.ondblclick = function() {
             handleDeleteRow(this.id);
            };
  }
}

function deleteTable(obj) {
  var tableId = document.getElementById('videoTableList');
  if (tableId)
  {
    for (var i = 0; i < tableId.rows.length; i++) {
        tableId.deleteRow(i);
    }
    tableId.deleteTHead();
  }

}

/* Handle updating the Rented status */
handleCheckedRow = function(idx) {
  /* Find the checked item */
  rowId = idx.substring(3,idx.length);
  console.log(rowId);
  var buttonId = document.getElementById(idx);
  if (videoList.list[rowId].rented) {
    tmpStr = 'checkin=' + videoList.list[rowId].id;
    sendRequest(tmpStr);
    buttonId.value = "Check Out";
    videoList.list[rowId].rented = false;
  }
  else {
   tmpStr = 'checkout=' + videoList.list[rowId].id;
   sendRequest(tmpStr);
   buttonId.value = "Check In";
   videoList.list[rowId].rented = true;
  }
};

/* Handle removing from the favorites */
handleDeleteRow = function(idx) {
  /* Find the checked item */
  var rowId = idx.substring(3,idx.length);
  var rowIdStr = ['row' + rowId];
  console.log(rowId);
  //Reference
  // http://stackoverflow.com/questions/4967223/javascript-delete-a-row-from-a-table-by-id
  var row = document.getElementById(rowIdStr);
  row.parentNode.removeChild(row);
  tmpStr = 'deleteRow=' + videoList.list[rowId].id;
  sendRequest(tmpStr);
  videoList.deleteObjFromList(rowId);
  
  // redraw the table from new list because IDs change
  deleteTable(videoList);
  generate_table(videoList);
  
};


var handleDeleteAll = function() {
  sendRequest("deleteAll");
  deleteTable(videoList);
}

var handleInsert = function() {
  var name = document.getElementsByName('name')[0].value;
  var category = document.getElementsByName('category')[0].value;
  var length = document.getElementsByName('length')[0].value;

  if ((!name) || (name.length < 1)) {
    alert("Enter a name!");
  } else {
    if (!category) {
      category = 'unknown';
    } 
    tmpStr = ['insert=true,name=' + name + ',category=' + category + ',length=' + length];
    sendRequest(tmpStr);
    sendRequest('getVideoList');
    deleteTable(videoList);
    generate_table(videoList);
  }
}



window.onload = function() {
  sendRequest('getVideoList');
  console.log('sent Request');
  sendRequest('getCategories');
};

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
      handleResponse(http.responseText);
    }
  }
  http.send(null);
  return(http);
}
