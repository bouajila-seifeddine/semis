<!DOCTYPE html>
<html>
<head>
  <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
  <meta content="utf-8" http-equiv="encoding">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
  <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet' type='text/css'>
  <script type="text/javascript" src="{$dmUrl}"></script>
</head>
<body style="margin:0px;height:100%">
  <div style="height:100vh;overflow-y:hidden">
    <div id="app" style="height:100%;width:100%;position:relative" />
  </div>
  <script type="text/javascript">
    function startDataKick(controllerUrl, baseUrl, rewrite, data, debug, translations) {
      var url = controllerUrl + '&ajax=true&action=Service';

      var getUrl = function getUrl(command) {
        var ret = url + '&service=' + command;
        if (debug)
          ret += '&debug-mode=true';
        return ret;
      };

      var getEndpointUrl = function getEndpointUrl(endpoint, full, parameters) {
        full = !!full;
        parameters = parameters || {};
        var base;
        var params = [];
        if (rewrite) {
          base = (full ? baseUrl : '/') + 'endpoint/' + endpoint;
        } else {
          base = (full ? baseUrl : '/') + 'index.php';
          params.push('endpoint=' + encodeURIComponent(endpoint));
          if (full) {
            params.push('fc=module');
            params.push('module=datakick');
            params.push('controller=endpoint');
          }
        }
        for (var key in parameters) {
          if (parameters.hasOwnProperty(key)) {
            var value = parameters[key];
            params.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
          }
        }
        return params.length ? (base + '?' + params.join('&')) : base;
      };

      var tokens = { {foreach from=$dmTokens key=collection item=token name=tokens}{$collection}:'{$token}'{if !$smarty.foreach.tokens.last},{/if} {/foreach}}

      var getDrilldownUrl = function getDrilldownUrl(record, keys) {
        if (tokens[record]) {
          return tokens[record].replace('<key>', keys[0]);
        }
        return null;
      }

      xmlFeedApp('app', getUrl, getEndpointUrl, data, debug, translations, getDrilldownUrl);
    }
    startDataKick('{$dmLink}', '{$dmBase}', {$dmRewrite}, {$dmConfig|json_encode}, {$dmDebugMode}, {$dmTranslations|json_encode});
  </script>
</html>
