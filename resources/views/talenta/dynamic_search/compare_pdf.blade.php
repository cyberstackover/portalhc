<?php
// echo '<pre>';
// print_r($data);
// echo '</pre>';
// exit();
setlocale(LC_ALL, 'IND');
global $i;
$i = 0;
?>
<html>
<head>
    <style>
        /* custom */
        body,
        html, p, div, table {
            margin: 0;
            padding: 0;
            text-rendering: optimizeLegibility;
            font-size: 12px;
            color: #111315;
            font-family:"Book Antiqua";
            line-height: normal;
        }
        div{
            line-height:1;
        }

        h1 {
            
            text-align: center;
            font-size: 1.56em;
            line-height: 1;
            letter-spacing: 1px;
            margin:5px 0px;
        }

        hr{
            margin:8px 0px;
            color: #333;
            height:2px;
        }
        .subtitle{
            line-height:0;
            margin:0;
            font-size:10px;
        }
        table {
            font-weight:normal;
            border-collapse: collapse;
        }
        td{
            vertical-align:top;
        }
        /* custom */
        .page-break {
            page-break-after: always;
        }
        .page-break-avoid{
            page-break-inside: avoid;
        }
        /* default */
        .padding-0{
            padding: 0!important;
        }

        .width-33,.width-66,
        .width-15,.width-30,.width-45,.width-90,
        .width-50,.width-25,.width-75,.width-100 {
            float: left;
            padding: 0px 15px;
        }
        .width-33{
            width: 33.33333%;
        }
        .width-66{
            width: 66.667%;
        }
        .width-15{
            width: 15%;
        }
        .width-30{
            width: 30%;
        }
        .width-45{
            width: 45%;
        }
        .width-90{
            width: 90%;
        }
        .width-50 {
            width: 50%;
        }

        .width-25 {
            width: 25%;
        }

        .width-75 {
            width: 75%;
        }
        .width-85 {
            width: 85%;
        }
        .width-100 {
            width: 75%;
        }
        .clearfix{
            clear:both;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-nowrap {
            white-space: nowrap;
        }

        .text-lowercase {
            text-transform: lowercase;
        }

        .text-uppercase,
        .initialism {
            text-transform: uppercase;
        }

        .text-capitalize {
            text-transform: capitalize;
        }
        .margin-top-10 {
            margin-top:10px;
        }
        .margin-top-15 {
            margin-top:15px;
        }
        .margin-top-20 {
            margin-top:20px;
        }
        .margin-top-40 {
            margin-top:40px;
        }

        .border-top{
            border-top:3px solid #333;
            padding:0px 8px;
        }
        .left{
            float: left;
        }
        .bold{
            font-weight:bold;
        }
        .barcode {
            padding: 0.5mm;
            color: #000044;
            width:40px;
        }
        .barcodecell {
            text-align: center;
            vertical-align: top;
        }
        @page {
            size: auto;
            /*margin-header: 15mm;*/
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        @page heads1 {
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        @page heads2 {
            odd-footer-name: html_myfooter;
            even-footer-name: html_myfooter;
        }
        .head1 {
            page-break-before: right;
            page: heads1;
        }
        .head2 {
            page-break-before: right;
            page: heads2;
        }
        /* default */

        
        .foto-talenta {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            height: 100px;
        }
        tr.border_bottom td{
            border-bottom: 1px solid #d2d2d2;
            /* border-right: 1px solid #d2d2d2; */
        }
        table {
            border-collapse:separate; 
            font-size: 10px;
            text-align:center;
        }
        table td {
            padding:7px;
        }
    </style>
</head>
<body>
    <div class="padding-0"> 
        <div class="margin-top-40">
            <table width="100%">
                <tr class="border_bottom">
                    <td width="20%"><b></b></td>
                    @foreach ($talentas as $talenta)
                    <td width="27%" style="padding-bottom:15px;">
                        <img class="foto-talenta" src="{{ \App\Helpers\CVHelper::getFoto($talenta->foto, ($talenta->jenis_kelamin == 'Laki-laki' ? 'img/male.png': 'img/female.png')) }}" alt=""> <br>
                        <span><b>{{$talenta->nama_lengkap}}</b></span><br>
                    </td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>NIK</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nik}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Jabatan</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->jabatan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>BUMN</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nama_perusahaan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Jenis Kelamin</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->jenis_kelamin}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Email</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->email}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>No Handphone</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->nomor_hp}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Tempat Lahir</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->tempat_lahir}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Tanggal Lahir</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{\App\Helpers\CVHelper::tglformat(@$talenta->tanggal_lahir)}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Agama</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->agama}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Alamat</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->alamat}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Pendidikan</b></td>
                    @foreach ($talentas as $talenta)
                    <td>{{$talenta->pendidikan}}</td>
                    @endforeach
                </tr>
                <tr class="border_bottom">
                    <td style="text-align:left;"><b>Riwayat Jabatan</b></td>
                    @for($i = 0; $i < count($riwayat_jabatan); $i++)
                        <td>
                        @foreach ($riwayat_jabatan[$i] as $val)
                                - {{$val->jabatan . ' '. $val->perusahaan}} 
                                {{ '(' . $val->tahun_awal. (($val->tahun_akhir? '-'.$val->tahun_akhir:'')) . ')' }}
                                <br>
                        @endforeach
                        </td>
                    @endfor
                </tr>
                
                @foreach($dynamic_filter as $a)
                <tr class="border_bottom">
                    @if($a->submenu== 'TANI')
                        <td style="text-align:left;"><b>{{$a->submenu}}</b></td>
                        @foreach ($talentas as $talenta)
                        <td>{{ number_format($talenta->{$a->alias}) }}</td>
                        @endforeach
                    @elseif($a->submenu!= 'Nama' && $a->submenu!= 'Jabatan' && $a->submenu!= 'Jenis Kelamin' && $a->submenu!= 'Pendidikan')
                        <td style="text-align:left;"><b>{{$a->submenu}}</b></td>
                        @foreach ($talentas as $talenta)
                        <td>{{ $talenta->{$a->alias} }}</td>
                        @endforeach
                    @endif
                </tr>
                @endforeach
            </table>
        </div>

        <!--mpdf
            <htmlpagefooter name="myfooter">
                <div class="width-75 padding-0 text-left margin-top-20">
                    <i style="font-size:10px;">Copyright © HC Kementerian BUMN 2021</i>
                </div>
            </htmlpagefooter>
        <sethtmlpagefooter name="myfooter" value="on" />
        mpdf-->
    </div>

    
</body>
<html>