


<html>

<head>

<style type="text/css">
    body,div,table,thead,tbody,tfoot,tr,th,td,p { font-family:"Calibri"; font-size:x-small }
    a.comment-indicator:hover + comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em;  } 
    a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em;  } 
    comment { display:none;  } 
</style>

</head>

<body>
<table cellspacing="0" border="0">
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">BUMN</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Selected</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Nominated</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Eligible</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Qualified</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Jumlah</font></b></td>
</tr>


<?php
    use App\Helpers\CVHelper;
?>

{{$num = 1 }}
@foreach($talenta as $a)
<tr>
    <td>{{$num++}}</td>
    <td>{{$a->bumn}}</td>
    <td>{{$a->jumlah_selected}}</td>
    <td>{{$a->jumlah_nominated}}</td>
    <td>{{$a->jumlah_eligible}}</td>
    <td>{{$a->jumlah_qualified}}</td>
    <td>{{$a->jumlah_selected+$a->jumlah_nominated+$a->jumlah_eligible+$a->jumlah_qualified}}</td>
</tr>
@endforeach

</table>
<!-- ************************************************************************** -->
</body>

</html>
