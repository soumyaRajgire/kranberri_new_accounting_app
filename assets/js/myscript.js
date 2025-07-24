function openTab(evt, tabName) {
    var i, tabContent, tabButton;
    
    // Get all tab content elements
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }
    
    // Show the selected tab content
    document.getElementById(tabName).style.display = "block";
    
    // Remove the "active" class from all tab buttons in the first set
    tabButton = document.querySelectorAll(".tabs:first-child .tab-button");
    for (i = 0; i < tabButton.length; i++) {
        tabButton[i].className = tabButton[i].className.replace(" active", "");
    }
    
    // Remove the "active" class from all tab buttons in the second set
    tabButton = document.querySelectorAll(".tabs:last-child .tab-button");
    for (i = 0; i < tabButton.length; i++) {
        tabButton[i].className = tabButton[i].className.replace(" active", "");
    }
    
    // Add the "active" class to the clicked tab button
    evt.currentTarget.className += " active";
}




