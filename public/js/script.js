// Function to toggle the sidebar's visibility
function toggleSidebar() {
   $("#wrapper").toggleClass("toggled");
   $("#wrapper").toggleClass("toggled-2");
   if($("#wrapper").hasClass("toggled-2")) {
    //    $(".nav-text").addClass("d-none");
       $(".menu-label").addClass("d-none");
       $(".toggle-menu").removeClass("d-none");
   } else {
       $(".nav-text").removeClass("d-none");
       $(".menu-label").removeClass("d-none");
       $(".toggle-menu").removeClass("d-none");
       $("#toggle-logo").addClass("d-none");
   }
       // Ensure wrapper takes the whole viewport height
       $("#sidebar-wrapper").css('min-height', '100vh');
}

// Function to handle clicks on sidebar items
function activeList() {
   $('.sidebar-nav a').click(function() {
       if($(window).width() <= 992) {
           sessionStorage.setItem('toggleSidebar', 'true');
       }
   });
}


// Function to automatically toggle the sidebar based on session storage and window width after page load
function checkToggleSidebarOnLoad() {
   if(sessionStorage.getItem('toggleSidebar') === 'true' && $(window).width() <= 992) {
       toggleSidebar();
       sessionStorage.removeItem('toggleSidebar'); // Clear the flag
   }
}

// Function to handle sidebar behavior on window resize
function handleResize() {
   $(window).resize(function() {
       if($(window).width() > 992 && $("#wrapper").hasClass("toggled")) {
           toggleSidebar(); // This ensures the sidebar is shown when above 992px
       }
   });
}

function toggleSidebarOnResize() {
   $(window).resize(function() {
       var windowWidth = $(window).width();
       if(windowWidth <= 992) {
           if(!$("#wrapper").hasClass("toggled")) {
               toggleSidebar();
           }
       } else {
           if($("#wrapper").hasClass("toggled")) {
               toggleSidebar();
           }
       }
   });
}

$(document).ready(function() {
   // Initialize the sidebar and other components
   initMenu();
   activeList();
   toggleSidebarOnResize()
   checkToggleSidebarOnLoad(); // Check if the sidebar needs to be toggled on page load
   handleResize(); // Handle window resize events

   $('#menu-toggle').click(function() {
        var icon = $('#menu-icon');
        if (icon.hasClass('bi-layout-sidebar')) {
            icon.removeClass('bi-layout-sidebar').addClass('bi-layout-sidebar-inset');
        } else {
            icon.removeClass('bi-layout-sidebar-inset').addClass('bi-layout-sidebar');
        }
    });

   // Attach click event to menu toggles
   $("#menu-toggle, #menu-toggle-2").click(function(e) {
       e.preventDefault();
       toggleSidebar();
   });

   // Function to initialize the menu
   function initMenu() {
       $('#menu ul').hide();
       $('#menu ul').children('.current').parent().show();
       $('#menu li a').click(function() {
           var checkElement = $(this).next();
           if ((checkElement.is('ul')) && (checkElement.is(':visible'))) {
               return false;
           }
           if ((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
               $('#menu ul:visible').slideUp('normal');
               checkElement.slideDown('normal');
               return false;
           }
       });
   }
});