
<!-- NAV BAR -->
<div class="navbar-wrapper">
    <div class="container-fluid">
        <nav class="navbar navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Logo</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="memberArea.php">Home</a></li>
                        
                        <li class=""><a href="landingPage.php">Create Landing Page</a></li>
                                                
                        <li><a href="sendSMS.php">Send SMS</a></li>
                        
                        <li><a href="contacts.php">Contacts</a>
                                                
                        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#">My Pages</a></li>
                                <li><a href="#">Reports</a></li>
                            </ul>
                        </li>
                     
                     </ul>
                    
                    
 <ul class="nav navbar-nav pull-right">
  <li class="dropdown">
   <a href="#" class="dropdown-toggle active" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Signed in as <?php echo $_SESSION['username']; ?> <span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a href="">My Credit</a></li>
        <li><a href="reset.php">Change Password</a></li>
        <li><a href="profile.php">My Profile</a></li>
      </ul>
   </li>
  <li class=""><a href="logout.php">Logout</a></li>
 </ul>
                    
                </div>
            </div>
        </nav>
    </div>
</div>

<!-- NAV BAR -->
