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
    for (var i = parseInt(id); i < this.list.length; i++)
    {
      this.list[i] = this.list[(i + 1)];
    }
    this.list.pop();
  };

  this.deleteAllObjFromList = function() {
    for (var i = 0; i < this.list.length; )
    {
      this.list.pop();
    }
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
    deleteTable();
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
          alert(
          'Operation failed, most likely the Name is not unique, try again!');
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

var listCategories = function(categories) {
  for (var i = 0; i < categories.length; i++) {
    console.log(categories[i]);
  }
  generate_categories(categories);
};

/* Generate a table around VideoObjList */
/* Reference: */
/* https://developer.mozilla.org/en-US/docs/Traversing_an_HTML_table_with_JavaScript_and_DOM_Interfaces */
function generate_table(arr) {
  if ((arr) && (arr.list.length > 0)) {
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
    tblHead4.innerHTML = 'Rented (click)';
    row.appendChild(tblHead4);
    var tblHead5 = document.createElement('th');
    tblHead5.innerHTML = 'Delete (double click)';
    row.appendChild(tblHead5);
    tblHeader.appendChild(row);

    var tblBody = document.createElement('tbody');

    // creating all cells
    for (var i = 0; i < arr.list.length; i++) {
      // creates a table row
      var row = document.createElement('tr');
      row.id = ['row' + i];
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
      cellChkBox.id = ['chk' + i];
      cellChkBox.checked = false;
      if (arr.list[i].rented) {
        cellChkBox.value = 'Checked Out';
      }
      else {
        cellChkBox.value = 'Available';
     }

      var cell5 = document.createElement('td');
      var cellDelete = document.createElement('input');

      cellDelete.type = 'button';
      cellDelete.id = ['del' + i];
      cellDelete.value = 'Delete';

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
}


function generate_categories(arr) {
  // creates a list of options to select
  var elementId = document.getElementById('newCategory');
  var option = document.createElement('option');
  option.id = 'all';
  option.text = 'All';
  //option.onclick = function() {handleCategorySelection(this.text);};
  elementId.appendChild(option);
  for (var i = 0; i < arr.length; i++) {
    var option = document.createElement('option');
    option.id = arr[i];
    option.text = arr[i];
    //option.onclick = function() {handleCategorySelection(this.text);};
    elementId.appendChild(option);
  }
}

function deleteCategories() {
  var tblId = document.getElementById('newCategory');
  if ((tblId) && (tblId.options))
  {
    for (var i = 0; i < tblId.options.length; ) {
        tblId.remove(i);
    }
  }
}



function deleteTable() {
  var tblId = document.getElementById('videoTableList');
  if ((tblId) && (tblId.rows))
  {
    for (var i = 0; i < tblId.rows.length; ) {
        tblId.deleteRow(i);
    }
    //Reference
    // http://stackoverflow.com/questions/2688602/delete-the-entire-table-rendered-from-different-pages-using-javascript
    tblId.parentNode.removeChild(tblId);
  }

}

/* Handle updating the Rented status */
handleCheckedRow = function(idx) {
  /* Find the checked item */
  rowId = idx.substring(3, idx.length);
  console.log(rowId);
  var buttonId = document.getElementById(idx);
  if (videoList.list[rowId].rented) {
    tmpStr = 'checkin=' + videoList.list[rowId].id;
    sendRequest(tmpStr);
    buttonId.value = 'Checked Out';
    videoList.list[rowId].rented = false;
  }
  else {
   tmpStr = 'checkout=' + videoList.list[rowId].id;
   sendRequest(tmpStr);
   buttonId.value = 'Available';
   videoList.list[rowId].rented = true;
  }
};

/* Handle removing from the favorites */
handleDeleteRow = function(idx) {
  /* Find the checked item */
  var rowId = idx.substring(3, idx.length);
  var rowIdStr = ['row' + rowId];
  console.log(rowId);
  //Reference
  // http://stackoverflow.com/questions/4967223/javascript-delete-a-row-from-a-table-by-id
  var row = document.getElementById(rowIdStr);
  //row.parentNode.removeChild(row);
  tmpStr = 'deleteRow=' + videoList.list[rowId].id;
  sendRequest(tmpStr);
  videoList.deleteObjFromList(rowId);

  // redraw the table from new list because IDs change
  deleteCategories();
  sendRequest('getCategories');
  deleteTable();
  generate_table(videoList);
};


var handleDeleteAll = function() {
  sendRequest('deleteAll');
  deleteTable();
  deleteCategories();
  sendRequest('getCategories');
};

var handleInsert = function() {
  var name = document.getElementsByName('name')[0].value;
  var category = document.getElementsByName('category')[0].value;
  var length = document.getElementsByName('length')[0].value;

  if ((!name) || (name.length < 1)) {
    alert('Enter a Name!');
  } else {
    if ((length) && (length < 0)) {
      alert('Length must be a positive integer!');
    }
    else {

      if (!category) {
        category = 'unknown';
      }
      tmpStr = ['insert=true&name=' + name +
                '&category=' + category + '&length=' + length];
      sendRequest(tmpStr);
      videoList.deleteAllObjFromList();
      deleteCategories();
      sendRequest('getCategories');
      // Reference:
      // http://www.w3schools.com/jsref/coll_select_options.asp
      catId = document.getElementById('newCategory');
      catVal = catId.options[catId.selectedIndex].text;
      tmpStr = ['getVideoList=' + catVal];
      sendRequest(tmpStr);
      deleteTable();
      generate_table(videoList);
    }
  }
};

handleCategorySelection = function() {
  videoList.deleteAllObjFromList();
  // Reference:
  // http://www.w3schools.com/jsref/coll_select_options.asp
  catId = document.getElementById('newCategory');
  catVal = catId.options[catId.selectedIndex].text;
  tmpStr = ['getVideoList=' + catVal];
  sendRequest(tmpStr);
  deleteTable();
  generate_table(videoList);
};


window.onload = function() {
  sendRequest('getCategories');
  // Reference:
  // http://www.w3schools.com/jsref/coll_select_options.asp
  catId = document.getElementById('newCategory');
  catVal = catId.options[catId.selectedIndex].text;
  tmpStr = ['getVideoList=' + catVal];
  sendRequest(tmpStr);
};

//References:
// http://stackoverflow.com/questions/9713058/sending-post-data-with-a-xmlhttprequest
// http://www.openjs.com/articles/ajax_xmlhttp_using_post.php
function sendRequest(params) {  // params is a stringify'd set of key values
  var http = new XMLHttpRequest();
  var url = 'videoLibrary.php';

  http.open('GET', url + '?' + params, false);
  http.onreadystatechange = function() {//Call a function when the state changes
    if (http.readyState == 4 && http.status == 200) {
      //alert(http.responseText);
      handleResponse(http.responseText);
    }
  };
  http.send(null);
  return (http);
}
