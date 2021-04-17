function getServerInfo() {
  $('.jkaData').html('');
    $.ajax({
      url: "./Scripts/getPlayers.php",
      type: "POST",
      success: function (jkaData) {

        // create playername list
        for (let i = 0; i < jkaData['players'].length; i++) {
          
          const playername = jkaData['players'][i][1];

          // if PING data !== 0 it is a player
          // otherwise its a bot
          if(jkaData['players'][i][0] !== '0' ){
            $('#playerlist').append('<li>' + playername + '</li>');
          } else {
            $('#botlist').append('<li>' + playername + '</li>');
          }
        }

        // output server info
        $('#plugin').append(jkaData['mod']);
        $('#map').append(jkaData['map']);
      },
      dataType: "json"
    });
  }
  getServerInfo()
