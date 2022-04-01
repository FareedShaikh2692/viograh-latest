<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Jquery plugin for family tree</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width"/>
        <link href="{{ asset('css/jquery-ui.css')}}" rel="stylesheet" />
        <link href="{{ asset('css/tree.css')}}" rel="stylesheet" />
        <script src="{{ asset('js/jquery-1.12.0.min.js') }}"></script>
        <script src="{{ asset('js/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/ps-family.js') }}"></script>
    </head>
    <body>
        
        <div id="pk-family-tree">
        </div>
        <script>
            $('#pk-family-tree').pk_family();
            $('#pk-family-tree').pk_family_create(
             {
				 data: '{"li0":{"a0":{"name":"1","age":"wewe","gender":"Male","pic":"images/profile.png"},"a1":{"name":"2","age":"23","gender":"Female","relation":"Spouse","pic":"images/profile-f.png"},"ul":{"li0":{"a0":{"name":"3","age":"34","gender":"Male","relation":"Child","pic":"images/profile.png"}},"li1":{"a0":{"name":"4","age":"34","gender":"Female","relation":"Child","pic":"images/profile-f.png"}}}}}'
				 }
             );
           
        </script>
    </body>
</html>
