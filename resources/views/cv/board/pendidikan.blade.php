


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
	<colgroup span="7" width="64"></colgroup>
	<tr>
		<td height="49" align="left" valign=middle><font face="Arial" size=4 color="#000000"><br></font></td>
		<td align="left" valign=middle><font face="Arial" size=4 color="#000000"><br></font></td>
		<td></td>
		</tr>
	<tr>
		<td height="20" align="left" valign=middle><font face="Arial" size=4 color="#000000"><br></font></td>
		<td align="left" valign=middle><font face="Arial" size=4 color="#000000"></font></td>
		<td style="background-color : #8f8e8e ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">No</font></b></td>
		<td style="background-color : #8f8e8e ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle ><b><font face="Arial" size=4 >ID</font></b></td>
		<td style="background-color : #8f8e8e ;border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" colspan=7 align="left" valign=middle ><b><font face="Arial" size=4 color="#000000">Jenjang Pendidikan</font></b></td>
        </tr>


    {{$num = 1 }}
    @foreach($JenjangPendidikan as $jp)
	<tr>
		<td height="30" align="left" valign=middle><font face="Arial" size=4 color="#000000"><br></font></td>
		<td align="left" valign=middle><font face="Arial" size=4 color="#000000"></font></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 2px solid #000000; border-right: 1px solid #000000" align="left" valign=middle sdval="1" sdnum="1033;"><font face="Arial" size=15 color="#000000">{{$num++}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="left" valign=middle><font face="Arial" size=4 color="#000000">{{$jp->id}}</font></td>
		<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 2px solid #000000" colspan=7 align="left" valign=middle><font face="Arial" size=4 color="#000000">{{$jp->nama}}</font></td>
        </tr>
    @endforeach
	
</table>
<!-- ************************************************************************** -->
</body>

</html>
