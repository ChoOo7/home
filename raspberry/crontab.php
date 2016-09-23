*/3 * * * * /home/osmc/scripts/preloadFromSeedbox.php >> /tmp/preloadLog
0 23 * * * /home/osmc/scripts/preloadSetSpeed.php 5000
30 6 * * * /home/osmc/scripts/preloadSetSpeed.php 2000
59 3 * * * /home/osmc/scripts/preloadResetCurrentSpeed.php
* * * * * mount /mnt/redbox/ > /dev/null
