var fileTypes = ['pdf', 'jpg', 'jpeg', 'png'];  //acceptable file types
function readURL(input) {
    if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

        if (isSuccess) { //yes
          // console.log('value primero=>'+$(input).val());
            var reader = new FileReader();
            reader.onload = function (e) {
                if (extension == 'pdf'){
                	$(input).closest('.fileUpload').find(".icon").attr('src','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCAzMDkuMjY3IDMwOS4yNjciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDMwOS4yNjcgMzA5LjI2NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxnPjxwYXRoIHN0eWxlPSJmaWxsOiNFMjU3NEM7IiBkPSJNMzguNjU4LDBoMTY0LjIzbDg3LjA0OSw4Ni43MTF2MjAzLjIyN2MwLDEwLjY3OS04LjY1OSwxOS4zMjktMTkuMzI5LDE5LjMyOUgzOC42NThjLTEwLjY3LDAtMTkuMzI5LTguNjUtMTkuMzI5LTE5LjMyOVYxOS4zMjlDMTkuMzI5LDguNjUsMjcuOTg5LDAsMzguNjU4LDB6Ii8+PHBhdGggc3R5bGU9ImZpbGw6I0I1MzYyOTsiIGQ9Ik0yODkuNjU4LDg2Ljk4MWgtNjcuMzcyYy0xMC42NywwLTE5LjMyOS04LjY1OS0xOS4zMjktMTkuMzI5VjAuMTkzTDI4OS42NTgsODYuOTgxeiIvPjxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNMjE3LjQzNCwxNDYuNTQ0YzMuMjM4LDAsNC44MjMtMi44MjIsNC44MjMtNS41NTdjMC0yLjgzMi0xLjY1My01LjU2Ny00LjgyMy01LjU2N2gtMTguNDRjLTMuNjA1LDAtNS42MTUsMi45ODYtNS42MTUsNi4yODJ2NDUuMzE3YzAsNC4wNCwyLjMsNi4yODIsNS40MTIsNi4yODJjMy4wOTMsMCw1LjQwMy0yLjI0Miw1LjQwMy02LjI4MnYtMTIuNDM4aDExLjE1M2MzLjQ2LDAsNS4xOS0yLjgzMiw1LjE5LTUuNjQ0YzAtMi43NTQtMS43My01LjQ5LTUuMTktNS40OWgtMTEuMTUzdi0xNi45MDNDMjA0LjE5NCwxNDYuNTQ0LDIxNy40MzQsMTQ2LjU0NCwyMTcuNDM0LDE0Ni41NDR6IE0xNTUuMTA3LDEzNS40MmgtMTMuNDkyYy0zLjY2MywwLTYuMjYzLDIuNTEzLTYuMjYzLDYuMjQzdjQ1LjM5NWMwLDQuNjI5LDMuNzQsNi4wNzksNi40MTcsNi4wNzloMTQuMTU5YzE2Ljc1OCwwLDI3LjgyNC0xMS4wMjcsMjcuODI0LTI4LjA0N0MxODMuNzQzLDE0Ny4wOTUsMTczLjMyNSwxMzUuNDIsMTU1LjEwNywxMzUuNDJ6IE0xNTUuNzU1LDE4MS45NDZoLTguMjI1di0zNS4zMzRoNy40MTNjMTEuMjIxLDAsMTYuMTAxLDcuNTI5LDE2LjEwMSwxNy45MThDMTcxLjA0NCwxNzQuMjUzLDE2Ni4yNSwxODEuOTQ2LDE1NS43NTUsMTgxLjk0NnogTTEwNi4zMywxMzUuNDJIOTIuOTY0Yy0zLjc3OSwwLTUuODg2LDIuNDkzLTUuODg2LDYuMjgydjQ1LjMxN2MwLDQuMDQsMi40MTYsNi4yODIsNS42NjMsNi4yODJzNS42NjMtMi4yNDIsNS42NjMtNi4yODJ2LTEzLjIzMWg4LjM3OWMxMC4zNDEsMCwxOC44NzUtNy4zMjYsMTguODc1LTE5LjEwN0MxMjUuNjU5LDE0My4xNTIsMTE3LjQyNSwxMzUuNDIsMTA2LjMzLDEzNS40MnogTTEwNi4xMDgsMTYzLjE1OGgtNy43MDN2LTE3LjA5N2g3LjcwM2M0Ljc1NSwwLDcuNzgsMy43MTEsNy43OCw4LjU1M0MxMTMuODc4LDE1OS40NDcsMTEwLjg2MywxNjMuMTU4LDEwNi4xMDgsMTYzLjE1OHoiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+');
                }
                else if (extension == 'png'){ $(input).closest('.fileUpload').find(".icon").attr('src','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBhdGggc3R5bGU9ImZpbGw6IzY1OUMzNTsiIGQ9Ik00OC4wMzcsNTZINy45NjNDNy4xNTUsNTYsNi41LDU1LjM0NSw2LjUsNTQuNTM3VjM5aDQzdjE1LjUzN0M0OS41LDU1LjM0NSw0OC44NDUsNTYsNDguMDM3LDU2eiIvPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiNEOUQ3Q0E7IiBwb2ludHM9IjM3LjUsMC4xNTEgMzcuNSwxMiA0OS4zNDksMTIgIi8+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0xNy4zODUsNTNoLTEuNjQxVjQyLjkyNGgyLjg5OGMwLjQyOCwwLDAuODUyLDAuMDY4LDEuMjcxLDAuMjA1YzAuNDE5LDAuMTM3LDAuNzk1LDAuMzQyLDEuMTI4LDAuNjE1YzAuMzMzLDAuMjczLDAuNjAyLDAuNjA0LDAuODA3LDAuOTkxczAuMzA4LDAuODIyLDAuMzA4LDEuMzA2YzAsMC41MTEtMC4wODcsMC45NzMtMC4yNiwxLjM4OGMtMC4xNzMsMC40MTUtMC40MTUsMC43NjQtMC43MjUsMS4wNDZjLTAuMzEsMC4yODItMC42ODQsMC41MDEtMS4xMjEsMC42NTZzLTAuOTIxLDAuMjMyLTEuNDQ5LDAuMjMyaC0xLjIxN1Y1M3ogTTE3LjM4NSw0NC4xNjh2My45OTJoMS41MDRjMC4yLDAsMC4zOTgtMC4wMzQsMC41OTUtMC4xMDNjMC4xOTYtMC4wNjgsMC4zNzYtMC4xOCwwLjU0LTAuMzM1YzAuMTY0LTAuMTU1LDAuMjk2LTAuMzcxLDAuMzk2LTAuNjQ5YzAuMS0wLjI3OCwwLjE1LTAuNjIyLDAuMTUtMS4wMzJjMC0wLjE2NC0wLjAyMy0wLjM1NC0wLjA2OC0wLjU2N2MtMC4wNDYtMC4yMTQtMC4xMzktMC40MTktMC4yOC0wLjYxNWMtMC4xNDItMC4xOTYtMC4zNC0wLjM2LTAuNTk1LTAuNDkyYy0wLjI1NS0wLjEzMi0wLjU5My0wLjE5OC0xLjAxMi0wLjE5OEgxNy4zODV6Ii8+PHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0zMS4zMTYsNDIuOTI0VjUzaC0xLjY2OGwtMy45NTEtNi45NDVWNTNoLTEuNjY4VjQyLjkyNGgxLjY2OGwzLjk1MSw2Ljk0NXYtNi45NDVIMzEuMzE2eiIvPjxwYXRoIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBkPSJNNDEuMTYsNDcuODA1djMuODk2Yy0wLjIxLDAuMjY1LTAuNDQ0LDAuNDgtMC43MDQsMC42NDlzLTAuNTMzLDAuMzA4LTAuODIsMC40MTdTMzkuMDUyLDUyLjk1NCwzOC43NDcsNTNjLTAuMzA2LDAuMDQ2LTAuNjA4LDAuMDY4LTAuOTA5LDAuMDY4Yy0wLjYwMiwwLTEuMTU1LTAuMTA5LTEuNjYxLTAuMzI4cy0wLjk0OC0wLjU0Mi0xLjMyNi0wLjk3MWMtMC4zNzgtMC40MjktMC42NzUtMC45NjYtMC44ODktMS42MTNjLTAuMjE0LTAuNjQ3LTAuMzIxLTEuMzk1LTAuMzIxLTIuMjQyczAuMTA3LTEuNTkzLDAuMzIxLTIuMjM1YzAuMjE0LTAuNjQzLDAuNTEtMS4xNzgsMC44ODktMS42MDZjMC4zNzgtMC40MjksMC44MjItMC43NTQsMS4zMzMtMC45NzhjMC41MS0wLjIyNCwxLjA2Mi0wLjMzNSwxLjY1NC0wLjMzNWMwLjU0NywwLDEuMDU3LDAuMDkxLDEuNTMxLDAuMjczYzAuNDc0LDAuMTgzLDAuODk3LDAuNDU2LDEuMjcxLDAuODJsLTEuMTM1LDEuMDEyYy0wLjIxOS0wLjI2NS0wLjQ3LTAuNDU2LTAuNzUyLTAuNTc0Yy0wLjI4My0wLjExOC0wLjU3NC0wLjE3OC0wLjg3NS0wLjE3OGMtMC4zMzcsMC0wLjY1OSwwLjA2My0wLjk2NCwwLjE5MWMtMC4zMDYsMC4xMjgtMC41NzksMC4zNDQtMC44MiwwLjY0OWMtMC4yNDIsMC4zMDYtMC40MzEsMC42OTktMC41NjcsMS4xODNzLTAuMjEsMS4wNzUtMC4yMTksMS43NzdjMC4wMDksMC42ODQsMC4wOCwxLjI3NiwwLjIxMiwxLjc3N2MwLjEzMiwwLjUwMSwwLjMxNCwwLjkxMSwwLjU0NywxLjIzczAuNDk3LDAuNTU2LDAuNzkzLDAuNzExYzAuMjk2LDAuMTU1LDAuNjA4LDAuMjMyLDAuOTM3LDAuMjMyYzAuMSwwLDAuMjM0LTAuMDA3LDAuNDAzLTAuMDIxYzAuMTY4LTAuMDE0LDAuMzM3LTAuMDM2LDAuNTA2LTAuMDY4YzAuMTY4LTAuMDMyLDAuMzMtMC4wNzUsMC40ODUtMC4xM2MwLjE1NS0wLjA1NSwwLjI2OS0wLjEzMiwwLjM0Mi0wLjIzMnYtMi40ODhoLTEuNzA5di0xLjEyMUg0MS4xNnoiLz48L2c+PGNpcmNsZSBzdHlsZT0iZmlsbDojRjNENTVCOyIgY3g9IjE4LjkzMSIgY3k9IjE0LjQzMSIgcj0iNC41NjkiLz48cG9seWdvbiBzdHlsZT0iZmlsbDojODhDMDU3OyIgcG9pbnRzPSI2LjUsMzkgMTcuNSwzOSA0OS41LDM5IDQ5LjUsMjggMzkuNSwxOC41IDI5LDMwIDIzLjUxNywyNC41MTcgIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg=='); 
                }
                else if (extension == 'jpg' || extension == 'jpeg'){
                	// $(input).closest('.fileUpload').find(".icon").attr('src','./vistas/images/img-file/jpg.svg');
                  $(input).closest('.fileUpload').find(".icon").attr('src','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0Q5RDdDQTsiIHBvaW50cz0iMzcuNSwwLjE1MSAzNy41LDEyIDQ5LjM0OSwxMiAiLz48Y2lyY2xlIHN0eWxlPSJmaWxsOiNGM0Q1NUI7IiBjeD0iMTguOTMxIiBjeT0iMTQuNDMxIiByPSI0LjU2OSIvPjxwb2x5Z29uIHN0eWxlPSJmaWxsOiMyNkI5OUE7IiBwb2ludHM9IjYuNSwzOSAxNy41LDM5IDQ5LjUsMzkgNDkuNSwyOCAzOS41LDE4LjUgMjksMzAgMjMuNTE3LDI0LjUxNyAiLz48cGF0aCBzdHlsZT0iZmlsbDojMTRBMDg1OyIgZD0iTTQ4LjAzNyw1Nkg3Ljk2M0M3LjE1NSw1Niw2LjUsNTUuMzQ1LDYuNSw1NC41MzdWMzloNDN2MTUuNTM3QzQ5LjUsNTUuMzQ1LDQ4Ljg0NSw1Niw0OC4wMzcsNTZ6Ii8+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yMS40MjYsNDIuNjV2Ny44NDhjMCwwLjQ3NC0wLjA4NywwLjg3My0wLjI2LDEuMTk2Yy0wLjE3MywwLjMyMy0wLjQwNiwwLjU4My0wLjY5NywwLjc3OWMtMC4yOTIsMC4xOTYtMC42MjcsMC4zMzMtMS4wMDUsMC40MUMxOS4wODUsNTIuOTYxLDE4LjY5Niw1MywxOC4yOTUsNTNjLTAuMjAxLDAtMC40MzYtMC4wMjEtMC43MDQtMC4wNjJjLTAuMjY5LTAuMDQxLTAuNTQ3LTAuMTA0LTAuODM0LTAuMTkxcy0wLjU2My0wLjE4NS0wLjgyNy0wLjI5NGMtMC4yNjUtMC4xMDktMC40ODgtMC4yMzItMC42Ny0wLjM2OWwwLjY5Ny0xLjEwN2MwLjA5MSwwLjA2MywwLjIyMSwwLjEzLDAuMzksMC4xOThjMC4xNjgsMC4wNjgsMC4zNTMsMC4xMzIsMC41NTQsMC4xOTFjMC4yLDAuMDYsMC40MSwwLjExMSwwLjYyOSwwLjE1N3MwLjQyNCwwLjA2OCwwLjYxNSwwLjA2OGMwLjQ4MywwLDAuODY4LTAuMDk0LDEuMTU1LTAuMjhzMC40MzktMC41MDQsMC40NTgtMC45NVY0Mi42NUgyMS40MjZ6Ii8+PHBhdGggc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGQ9Ik0yNS41MTQsNTIuOTMyaC0xLjY0MVY0Mi44NTVoMi44OThjMC40MjgsMCwwLjg1MiwwLjA2OCwxLjI3MSwwLjIwNWMwLjQxOSwwLjEzNywwLjc5NSwwLjM0MiwxLjEyOCwwLjYxNWMwLjMzMywwLjI3MywwLjYwMiwwLjYwNCwwLjgwNywwLjk5MXMwLjMwOCwwLjgyMiwwLjMwOCwxLjMwNmMwLDAuNTExLTAuMDg3LDAuOTczLTAuMjYsMS4zODhjLTAuMTczLDAuNDE1LTAuNDE1LDAuNzY0LTAuNzI1LDEuMDQ2Yy0wLjMxLDAuMjgyLTAuNjg0LDAuNTAxLTEuMTIxLDAuNjU2cy0wLjkyMSwwLjIzMi0xLjQ0OSwwLjIzMmgtMS4yMTdWNTIuOTMyeiBNMjUuNTE0LDQ0LjF2My45OTJoMS41MDRjMC4yLDAsMC4zOTgtMC4wMzQsMC41OTUtMC4xMDNjMC4xOTYtMC4wNjgsMC4zNzYtMC4xOCwwLjU0LTAuMzM1czAuMjk2LTAuMzcxLDAuMzk2LTAuNjQ5YzAuMS0wLjI3OCwwLjE1LTAuNjIyLDAuMTUtMS4wMzJjMC0wLjE2NC0wLjAyMy0wLjM1NC0wLjA2OC0wLjU2N2MtMC4wNDYtMC4yMTQtMC4xMzktMC40MTktMC4yOC0wLjYxNWMtMC4xNDItMC4xOTYtMC4zNC0wLjM2LTAuNTk1LTAuNDkyQzI3LjUsNDQuMTY2LDI3LjE2Myw0NC4xLDI2Ljc0NCw0NC4xSDI1LjUxNHoiLz48cGF0aCBzdHlsZT0iZmlsbDojRkZGRkZGOyIgZD0iTTM5LjUsNDcuNzM2djMuODk2Yy0wLjIxLDAuMjY1LTAuNDQ0LDAuNDgtMC43MDQsMC42NDlzLTAuNTMzLDAuMzA4LTAuODIsMC40MTdzLTAuNTgzLDAuMTg3LTAuODg5LDAuMjMyQzM2Ljc4MSw1Mi45NzgsMzYuNDc5LDUzLDM2LjE3OCw1M2MtMC42MDIsMC0xLjE1NS0wLjEwOS0xLjY2MS0wLjMyOHMtMC45NDgtMC41NDItMS4zMjYtMC45NzFjLTAuMzc4LTAuNDI5LTAuNjc1LTAuOTY2LTAuODg5LTEuNjEzYy0wLjIxNC0wLjY0Ny0wLjMyMS0xLjM5NS0wLjMyMS0yLjI0MnMwLjEwNy0xLjU5MywwLjMyMS0yLjIzNWMwLjIxNC0wLjY0MywwLjUxLTEuMTc4LDAuODg5LTEuNjA2YzAuMzc4LTAuNDI5LDAuODIyLTAuNzU0LDEuMzMzLTAuOTc4YzAuNTEtMC4yMjQsMS4wNjItMC4zMzUsMS42NTQtMC4zMzVjMC41NDcsMCwxLjA1NywwLjA5MSwxLjUzMSwwLjI3M2MwLjQ3NCwwLjE4MywwLjg5NywwLjQ1NiwxLjI3MSwwLjgybC0xLjEzNSwxLjAxMmMtMC4yMTktMC4yNjUtMC40Ny0wLjQ1Ni0wLjc1Mi0wLjU3NGMtMC4yODMtMC4xMTgtMC41NzQtMC4xNzgtMC44NzUtMC4xNzhjLTAuMzM3LDAtMC42NTksMC4wNjMtMC45NjQsMC4xOTFjLTAuMzA2LDAuMTI4LTAuNTc5LDAuMzQ0LTAuODIsMC42NDljLTAuMjQyLDAuMzA2LTAuNDMxLDAuNjk5LTAuNTY3LDEuMTgzcy0wLjIxLDEuMDc1LTAuMjE5LDEuNzc3YzAuMDA5LDAuNjg0LDAuMDgsMS4yNzYsMC4yMTIsMS43NzdjMC4xMzIsMC41MDEsMC4zMTQsMC45MTEsMC41NDcsMS4yM3MwLjQ5NywwLjU1NiwwLjc5MywwLjcxMWMwLjI5NiwwLjE1NSwwLjYwOCwwLjIzMiwwLjkzNywwLjIzMmMwLjEsMCwwLjIzNC0wLjAwNywwLjQwMy0wLjAyMWMwLjE2OC0wLjAxNCwwLjMzNy0wLjAzNiwwLjUwNi0wLjA2OGMwLjE2OC0wLjAzMiwwLjMzLTAuMDc1LDAuNDg1LTAuMTNjMC4xNTUtMC4wNTUsMC4yNjktMC4xMzIsMC4zNDItMC4yMzJ2LTIuNDg4aC0xLjcwOXYtMS4xMjFIMzkuNXoiLz48L2c+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==');
                }
                else {
                	//console.log('here=>'+$(input).closest('.uploadDoc').length);
                  // $(input).closest('.uploadDoc').val("");
                	$(input).closest('.uploadDoc').find(".docErr").slideUp('slow');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
        		// console.log('here=>'+$(input).closest('.uploadDoc').find(".docErr").length);
            // console.log('value=>'+$(input).val());
            $(input).closest('.uploadDoc').find(".docErr").fadeIn();
            $(input).closest('.fileUpload').find(".icon").attr('src','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0Q5RDdDQTsiIHBvaW50cz0iMzcuNSwwLjE1MSAzNy41LDEyIDQ5LjM0OSwxMiAiLz48cGF0aCBzdHlsZT0iZmlsbDojQzhCREI4OyIgZD0iTTQ4LjAzNyw1Nkg3Ljk2M0M3LjE1NSw1Niw2LjUsNTUuMzQ1LDYuNSw1NC41MzdWMzloNDN2MTUuNTM3QzQ5LjUsNTUuMzQ1LDQ4Ljg0NSw1Niw0OC4wMzcsNTZ6Ii8+PGNpcmNsZSBzdHlsZT0iZmlsbDojRkZGRkZGOyIgY3g9IjE4LjUiIGN5PSI0NyIgcj0iMyIvPjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIyOC41IiBjeT0iNDciIHI9IjMiLz48Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMzguNSIgY3k9IjQ3IiByPSIzIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==');
            // $(input).val("");
            setTimeout(function() {
				   	$('.docErr').fadeOut('slow');
					}, 9000);
        }
    }
}

$(document).ready(function(){
   
   $(document).on('change','.up', function(){
   	 var id = $(this).attr('id'); /* gets the filepath and filename from the input */
	   var profilePicValue = $(this).val();
	   var fileNameStart = profilePicValue.lastIndexOf('\\'); /* finds the end of the filepath */
	   profilePicValue = profilePicValue.substr(fileNameStart + 1).substring(0,20); /* isolates the filename */
	   //var profilePicLabelText = $(".upl"); /* finds the label text */
	   if (profilePicValue != '') {
      // console.log();
	   	console.log($(this).closest('.fileUpload').find('.upl').length);
	      $(this).closest('.fileUpload').find('.upl').html(profilePicValue); /* changes the label text */
	   }
   });
                  
   $("#btn-new").on('click',function(){
        // $("#uploader").append('<div class="row uploadDoc"><div class="col-sm-3"><div class="docErr">Please upload valid file</div><!--error--><div class="fileUpload btn btn-orange"> <img src="https://image.flaticon.com/icons/svg/136/136549.svg" class="icon"><span class="upl" id="upload">Upload document</span><input type="file" class="upload up" id="up" onchange="readURL(this);" /></div></div><div class="col-sm-8"><input type="text" class="form-control" name="" placeholder="Note"></div><div class="col-sm-1"><a class="btn-check"><i class="fa fa-times"></i></a></div></div>');
       $("#uploader").append(`
                <div class="row uploadDoc">
                  <div class="col-sm-4">
                    <div class="docErr">
                    <i class="fas fa-exclamation-circle mr-2 fa-sm"></i>
                       Por favor subir un archivo valido
                    </div><!--error-->
                    <div class="fileUpload btn btn-elegir">
                      <img class="icon" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTYgNTYiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDU2IDU2OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggc3R5bGU9ImZpbGw6I0U5RTlFMDsiIGQ9Ik0zNi45ODUsMEg3Ljk2M0M3LjE1NSwwLDYuNSwwLjY1NSw2LjUsMS45MjZWNTVjMCwwLjM0NSwwLjY1NSwxLDEuNDYzLDFoNDAuMDc0YzAuODA4LDAsMS40NjMtMC42NTUsMS40NjMtMVYxMi45NzhjMC0wLjY5Ni0wLjA5My0wLjkyLTAuMjU3LTEuMDg1TDM3LjYwNywwLjI1N0MzNy40NDIsMC4wOTMsMzcuMjE4LDAsMzYuOTg1LDB6Ii8+PHBvbHlnb24gc3R5bGU9ImZpbGw6I0Q5RDdDQTsiIHBvaW50cz0iMzcuNSwwLjE1MSAzNy41LDEyIDQ5LjM0OSwxMiAiLz48cGF0aCBzdHlsZT0iZmlsbDojQzhCREI4OyIgZD0iTTQ4LjAzNyw1Nkg3Ljk2M0M3LjE1NSw1Niw2LjUsNTUuMzQ1LDYuNSw1NC41MzdWMzloNDN2MTUuNTM3QzQ5LjUsNTUuMzQ1LDQ4Ljg0NSw1Niw0OC4wMzcsNTZ6Ii8+PGNpcmNsZSBzdHlsZT0iZmlsbDojRkZGRkZGOyIgY3g9IjE4LjUiIGN5PSI0NyIgcj0iMyIvPjxjaXJjbGUgc3R5bGU9ImZpbGw6I0ZGRkZGRjsiIGN4PSIyOC41IiBjeT0iNDciIHI9IjMiLz48Y2lyY2xlIHN0eWxlPSJmaWxsOiNGRkZGRkY7IiBjeD0iMzguNSIgY3k9IjQ3IiByPSIzIi8+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjwvc3ZnPg==" >
                      <span class="upl" id="upload">Elegir archivo</span>
                      <input type="file" class="upload up" id="up" name="archivos_multiples[]" onchange="readURL(this);" />
                    </div>
                  </div>
                  <div class="col-7">
                    <div class="group">
                      <input type="text" name="archivos_adjtitulo[]" />
                      <span class="highlight"></span>
                      <span class="bar"></span>
                      <label>Titulo</label>
                    </div>
                  </div>
                  <div class="col-1">
                  <a class="btn-check"><i class="fa fa-times"></i></a>
                  </div>
                  </div>
                `);
   });
    
   $(document).on("click", "a.btn-check" , function() {
     if($(".uploadDoc").length>1){
        $(this).closest(".uploadDoc").remove();
      }else{
        toastr.warning("No eliminar archivo unico","Archivos multiples");
      } 
   });

});