<footer style="position: fixed; bottom: 0; right: 0; background-color: #333; color: #fff; text-align: center; padding: 10px;width: 77%;
    margin-left: 260px !important;">
    All rights reserved. <a href="https://www.vert-age.com/" style="color: #7FFFAA;">Xenottabyte Services Pvt. Ltd.</a> | Code by: <a href="https://www.vert-age.com/" style="color: #7FFFAA;">Xenottabyte Services Pvt. Ltd.</a>
</footer>

</div>
</div>
</div>
<script src="{{ asset('/js/jquery-1.10.2.js') }}"></script>
<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('/js/custom-scripts.js') }}"></script>
<script src="{{ asset('js/jsclock-0.8.min.js') }}"></script>
{{-- <script src="{{ asset('js/main.js')}}"></script> --}}

<script>
        // Set the inactivity timeout (10 minutes)
        var inactivityTimeout = 100000; // 10 minutes in milliseconds

        // Variables to keep track of user activity
        var activityDetected = false;
        var logoutTimer;

        // Function to reset the logout timer
        function resetLogoutTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(logoutUser, inactivityTimeout);
            activityDetected = true;
        }

        // Function to log out the user
        function logoutUser() {
            // Redirect the user to the logout page or perform logout action
            window.location.href = '{{route('logout')}}'; // Replace with your logout URL
        }

        // Add event listeners for user activity
        document.addEventListener('mousemove', resetLogoutTimer);
        document.addEventListener('keydown', resetLogoutTimer);

        // Start monitoring for inactivity
        resetLogoutTimer();

        // Show a warning if the user is about to be logged out
        window.addEventListener('beforeunload', function (e) {
            if (activityDetected) {
                return undefined;
            }
            e.returnValue = 'You will be logged out due to inactivity.';
        });

        // Show a warning if the user tries to close the tab
        window.addEventListener('unload', function (e) {
            if (activityDetected) {
                return undefined;
            }
            e.returnValue = 'You will be logged out due to inactivity.';
        });
    </script>

</body>
   
</html>