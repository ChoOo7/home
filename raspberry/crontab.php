*/3 * * * * /var/home/raspberry/preloadFromSeedbox.php >> /tmp/preloadLog
0 23 * * * /var/home/raspberry/preloadSetSpeed.php 5000
30 6 * * * /var/home/raspberry/preloadSetSpeed.php 2000
59 3 * * * /var/home/raspberry/preloadResetCurrentSpeed.php
* * * * * mount /servers/redbox/ > /dev/null
