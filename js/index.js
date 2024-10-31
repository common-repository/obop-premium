(function() {

  tinymce.create("tinymce.plugins.obop_premium_plugin", {
    init : function(ed, url) { //url = absolute url of the plugin directory

      //adding a new button
      ed.addButton("readmore", {
        title: "OBOP Premium",
        cmd : "read_more_command",
        image: pluginUrl + "img/icon.png"
      });

      //button functionality
      ed.addCommand("read_more_command", function() {
        var localSource = pluginUrl + "img/read-more-" + curLang +".png"; //does not display on the post -> path is not the right one
        ed.execCommand('mceInsertContent', false, '<img class="readmore-button" alt="button read more" height="75" width="150" src="' + localSource + '" alt="Read more" data-type="more"/>');
      });
    },

    createControl : function(n, cm) {
      return null;
    },

    getInfo : function() {
      return {
        longname : "OBOP Premium",
        author : "OBOP",
        version : "0.1"
      };
    }
  });

  tinymce.PluginManager.add("obop_premium_plugin", tinymce.plugins.obop_premium_plugin);
})();
