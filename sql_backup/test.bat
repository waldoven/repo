set ano=%DATE:~6,4%
echo hoy es %ano%

set dia=%DATE:~0,2%
set mes=%DATE:~3,2%
set hor=%TIME:~0,2%
set min=%TIME:~3,2%

:: IF %day% LSS 10 SET day=0%day:~1,1%
:: IF %mnt% LSS 10 SET mnt=0%mnt:~1,1%
:: IF %hr% LSS 10 SET hr=0%hr:~1,1%
:: IF %min% LSS 10 SET min=0%min:~1,1%

set backuptime=%ano%_%mes%_%hor%_%min%
echo %backuptime%