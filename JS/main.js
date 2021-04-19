function getServerInfo() {
  $('.jkaData').html('');
    $.ajax({
      url: "./Scripts/getPlayers.php",
      type: "POST",
      data: {host: "64.94.95.202", port: "29070"},
      dataType: "json",
      success: function (jkaData) {

        createOnlineLists(jkaData);
        
        // output server info
        $('#plugin').append(jkaData['mod']);
        createMapInfos(jkaData);
      }
    });
  }
  getServerInfo()

  function createOnlineLists(jkaData){
    // create playername list
    for (let i = 0; i < jkaData['players'].length; i++) {    
      const playername = jkaData['players'][i][1];

      // if PING data == 0 it is a player
      const listtype = jkaData['players'][i][0] == '0' ? '#botlist' : '#playerlist';

      // Output playerlist
      $(listtype).append('<li>' + colorizeNames('^7' + playername) + '</li>');
    }

    createOnlineListCounters()

  }

  function createOnlineListCounters() {
    // count bots and players based off created ul li items
    const botcount = $('#botlist li').length 
    const playercount = $('#playerlist li').length
    // output counts and text based of count length
    $("#bot-heading").append(botcount > 2 || botcount == 0 ? botcount + ' Bots' : botcount + ' Bot');
    $("#player-heading").append(playercount > 1 || playercount == 0 ? playercount + ' Players' : playercount + ' Player');
  }

  function colorizeNames(playername){
    let colors = ['#000000','#f31415','#0af60a','#fefe00','#0d0df8','#0ef3f4','#fd02fe','#ffffff','#fd7d00', '#8a8e93']
    let prevCaret = playername.length;
    let colorizedName = '';

      for (let i = playername.length; i >= 0; i--) {
        if (playername.charAt(i) == '^' && (playername.charAt(i+1) >= '0' && playername.charAt(i+1) <= '9')) {
          
          let colorInt = parseInt(playername.charAt(i+1));
          let tintedChars = playername.substring(i+2, prevCaret);

          prevCaret = i;
          colorizedName = tintedChars.fontcolor(colors[colorInt]) + colorizedName;
        }
      }
      return colorizedName
  }

  function createMapInfos(jkaData) {
    $('#map').append(jkaData['map']);
  }