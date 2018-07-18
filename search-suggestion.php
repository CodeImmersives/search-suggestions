<!DOCTYPE html>
<html lang="en">

  <head>
    
    <meta http-equiv="Content-Language" content="en-us">
    
    <title>PHP MySQL Typeahead Autocomplete</title>
    
    <meta charset="utf-8">
    
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="http://netsh.pp.ua/upwork-demo/1/js/typeahead.js"></script>
    
    <style>
        
        body {
            background-color: #222;
        }
        
        h1 {
            font-size: 20px;
            color: #111;
        }

        .content {
            width: 80%;
            margin: 0 auto;
            margin-top: 50px;
        }

        .tt-hint,
        .city, .search-me {
            border: 2px solid #CCCCCC;
            border-radius: 8px 8px 8px 8px;
            font-size: 24px;
            height: 45px;
            line-height: 30px;
            outline: medium none;
            padding: 8px 12px;
            width: 400px;
        }

        .tt-dropdown-menu {
            width: 400px;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #fff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            border-radius: 8px 8px 8px 8px;
            font-size: 18px;
            color: #111;
            background-color: #F1F1F1;
        }
        
        .suggy {
            padding:5px 10px; 
            font-size:2rem; 
            color:#777; 
            border:1px solid #777; 
            width:400px; 
            list-style:none; 
            display:none;
            cursor: pointer;
        }
        
        .suggy li {
            padding: 5px 10px;
        }
        
        .suggy li:hover {
            background-color: #777;
            color: #DDD;
        }
        
        #mbr-profile p {
            font-size: 2rem;
        }
        
    </style>
    
    <script>
        
        $(document).ready(function() {
            
            // alert('jquery ready') -- works

            $('input.city').typeahead({
                name: 'city',
                remote: 'city.php?query=%QUERY'
            });

        })
        
    </script>
    
  </head>

  <body>
    
    <div class="content" style="border:4px solid #DDD; padding:20px; background-color:#BBB; height:95vh; margin:20px auto; width:95%; color:#333; border-radius:15px; overflow-y:scroll">
        
        <div style="float:left; width:44%; padding:20px; background-color:#EEE; min-height:500px; margin:1%; border-radius:10px; border:3px solid #888;">

            <form>

                <h1>Typeahead jQuery Plugin Search City or Zip</h1>
                <input type="text" name="city" size="30" class="city" 
                       placeholder="Please Enter City or ZIP code">

            </form>
        
        </div>
        
        <div style="float:right; width:44%; padding:20px; background-color:#EEE; min-height:500px; margin:1%; border-radius:10px; border:3px solid #888;">
        
            <div style="margin-top:40px">

                <h4>Vanilla Javascript Auto-Suggest: No Plugins or Libraries</h4>

                <h3>Search for Member:</h3>

                <form autocomplete="off">

                    <input type="search" name="search" size="30" class="search-me"
                           placeholder="Enter Player Name" oninput="getSuggestions()" onfocus="clearSearchBox()">

                    <div id="suggs">
                        <ul id="suggs-list" class="suggy"></ul>
                    </div>

                </form>

            </div>

            <div id="mbr-profile" style="margin-top:30px 0px 0 20px; background-color:beige; padding:15px; font-size:1.15rem; width:100%; min-height:500px; display:none; border:2px solid tan;">

                <img id="mbr-pic" style="margin:20px 10px 0; border:2px solid #777; width:250px; float:left">

                <h2 id="mbr-name"></h2>
                <p id="mbr-title"></p>
                <p id="mbr-company"></p>
                <p id="mbr-hobbies"></p>
                <p id="mbr-bio" style="max-width:600px"></p>

            </div>
            
        </div>

    </div><!-- end .content -->

    <script>
        
        let searchStr = "";
        const searchBox = document.querySelector('input.search-me');
        const suggsList = document.getElementById('suggs-list'); // the ul to populate
        
        const mbrProfile = document.getElementById('mbr-profile');
        const mbrPic = document.getElementById('mbr-pic');
        const mbrName = document.getElementById('mbr-name');
        const mbrTitle = document.getElementById('mbr-title');
        const mbrCompany = document.getElementById('mbr-company');
        const mbrHobbies = document.getElementById('mbr-hobbies');
        const mbrBio = document.getElementById('mbr-bio');

        function getSuggestions() {
            
            let suggestions = ""; // for storing the JSON returned by PHP
            // w each char typed, get the text that the user is entering into the input box
            searchStr = searchBox.value;
            
            var xhr = new XMLHttpRequest();
            
            xhr.onreadystatechange = function() {
                
                if(xhr.readyState == 4 && xhr.status == 200) {
                    // alert(xhr.responseText);
                    suggestions = JSON.parse(xhr.responseText); // parse the JSON
                    renderSuggestions(suggestions); // call the func that renders suggs list
                    
                } // end if..
                
            } // end onready..
            
            // send the AJAX request w a URL Variable: search equal to whatever user typed into the searchStr. This is updated w each key stroke
            xhr.open('GET', 'search-suggestion-proc.php?search=' + searchStr, true);
            xhr.send();
            
        } // function getInput()
        
        function renderSuggestions(suggs) { // the arg is the JS obj array w suggestions
            
            suggsList.innerHTML = "";
            suggsList.style.display = "block";
            
            for(let i = 0; i < suggs.length; i++) { // go through the JS obj arr
                
                let listItem = document.createElement('li'); // make an li-tag for the ul
                listItem.IDmbr = suggs[i].IDmbr;
                listItem.innerHTML = `${suggs[i].lastName}, ${suggs[i].firstName}  (${suggs[i].user})`; // popluate the li-tag, as: Smith, Joe (Joey1)
                // clicking a list item calls the chooseSuggestion function
                listItem.addEventListener('click', chooseSuggestion); 
                // output text to li-tag
                suggsList.appendChild(listItem); // append another li to the ul
                
            } // for
                        
        } // function renderSuggestions(suggs)
        
        function chooseSuggestion() {
            
            // populate input serach field with same text as list item 
            searchBox.value = event.target.innerHTML; // the contents of the chosen list item copied to the search box
            // clear suggestions now that a choice has been made
            suggsList.innerHTML = ""; 
            suggsList.style.display = "none";
            requestMemberData(event.target.IDmbr); //
                
        } // function chooseSuggestion()
        
        function requestMemberData(IDmbr) { // AJAX call for data for chosen member
            
            var xhr = new XMLHttpRequest();
            var memberArr = {} // an array w one item, an object w member data properties 
            
            xhr.onreadystatechange = function() {
                
                if(xhr.readyState == 4 && xhr.status == 200) {
                    // alert(xhr.responseText);
                    member = JSON.parse(xhr.responseText); // parse the JSON
                    renderMemberProfile(member); // call the func that renders suggs list
                    
                } // end if..
                
            } // end onready..
            
            // send the AJAX request w a URL Variable: search equal to whatever user typed into the searchStr. This is updated w each key stroke
            xhr.open('GET', 'request-member-data-proc.php?IDmbr=' + IDmbr, true);
            xhr.send();
            
        } // function requestMemberData(IDmbr)
        
        function renderMemberProfile(mbr) {
            
            mbrProfile.style.display = "block"; // show the hidden div

            // populate the mbr profile div child elements with the loaded data
            mbrPic.src = "members/" + mbr.user + "/images/" + mbr.imgName;
            mbrName.innerHTML = mbr.firstName + " " + mbr.lastName + " (" + mbr.user + ")";
            mbrTitle.innerHTML = mbr.jobTitle;
            mbrCompany.innerHTML = mbr.company;
            mbrHobbies.innerHTML = mbr.hobbies;
            mbrBio.innerHTML = mbr.aboutMe;
            
        } // function renderMemberProfile(mbr)
        
        function clearSearchBox() {
            
            searchBox.value = "";
            
        } // function clearSearchBox()
        
        function clearSuggestions() {
            
            suggsList.innerHTML = "";
            
        } // function clearSearchBox()
                  
    </script>

  </body>

</html>
