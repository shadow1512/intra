@extends('layouts.appmenu')

@section('content')
    <div class="main_news">
        <div class="h __h_m">Заявка на замену картриджа</div>
        @if(!is_null($user))
        <form class="profile_form" id="cartridge_change_form" action="{{route('services.store')}}" method="POST">
            <input type="hidden" name="type_request" id="type_request" value="cartridge"/>
            {{ csrf_field() }}
            <div class="field">
                <label for="roomnum" class="lbl">Уточните комнату, в которой стоит принтер:</label>
                <input type="text" id="roomnum" name="roomnum" class="it" value="{{$user->room}}" maxlength="10"/>
            </div>
            <div class="field">
                <label for="printer" class="lbl">Выберите модель принтера из списка:</label>
                <select id="printer" name="printer" class="form-control">
                    <option value="" selected="selected">Выберите принтер</option>
                    <option value="Другой">Другой</option>
                    <option value="Epson AcuLaser C3800 Желтый:Epson S051124 Желтый">Epson AcuLaser C3800 Желтый:Epson S051124 Желтый</option>
<option value="Epson AcuLaser C3800 Пурпурный:Epson S051125 Пурпурный">Epson AcuLaser C3800 Пурпурный</option>
<option value="Epson AcuLaser C3800 Черный:Epson S051127 Черный">Epson AcuLaser C3800 Черный</option>
<option value="Epson L1800 A3 Черный:Epson C13T67314A T6731 Черный">Epson L1800 A3 Черный</option>
<option value="Epson L1800 A3 Голубой:Epson C13T67324A T6732 Голубой">Epson L1800 A3 Голубой</option>
<option value="Epson L1800 A3 Пурпурный:Epson C13T67334A T6733 Пурпурный">Epson L1800 A3 Пурпурный</option>
<option value="Epson L1800 A3 Желтый:Epson C13T67344A T6734 Желтый">Epson L1800 A3 Желтый</option>
<option value="Epson L312 цв. С СНПЧ Черный:Epson C13T66414A 664Bk Черный">Epson L312 цв. С СНПЧ Черный</option>
<option value="Epson L312 цв. С СНПЧ Голубой:Epson C13T66424A 664C Голубой">Epson L312 цв. С СНПЧ Голубой</option>
<option value="Epson L312 цв. С СНПЧ Пурпурный:Epson C13T66434A 664M Пурпурный">Epson L312 цв. С СНПЧ Пурпурный</option>
<option value="Epson L312 цв. С СНПЧ Желтый:Epson C13T66444A 664Y Желтый">Epson L312 цв. С СНПЧ Желтый</option>
<option value="HP Color LaserJet Pro CP5225n Черный:CE740A HP 307A Черный">HP Color LaserJet Pro CP5225n Черный</option>
<option value="HP Color LaserJet Pro CP5225n Голубой:CE741A HP 307A Голубой">HP Color LaserJet Pro CP5225n Голубой</option>
<option value="HP Color LaserJet Pro CP5225n Желтый:CE742A HP 307A Желтый">HP Color LaserJet Pro CP5225n Желтый</option>
<option value="HP Color LaserJet Pro CP5225n Пурпурный:CE743A HP 307A Пурпурный">HP Color LaserJet Pro CP5225n Пурпурный</option>
<option value="HP Color LaserJet Pro M377 Черный:HP CF410A Черный">HP Color LaserJet Pro M377 Черный</option>
<option value="HP Color LaserJet Pro M377 Голубой:HP CF411A Голубой">HP Color LaserJet Pro M377 Голубой</option>
<option value="HP Color LaserJet Pro M377 Желтый:HP CF412A Желтый">HP Color LaserJet Pro M377 Желтый</option>
<option value="HP Color LaserJet Pro M377 Пурпурный:HP CF413A Пурпурный">HP Color LaserJet Pro M377 Пурпурный</option>
<option value="HP DJ 6122 Черный:HP 51645AE Черный">HP DJ 6122 Черный</option>
<option value="HP DJ 6122 Трёхцветный:HP C6578DE Трёхцветный">HP DJ 6122 Трёхцветный</option>
<option value="HP LaserJet 1200:C7115A">HP LaserJet 1200</option>
<option value="HP LaserJet 1300:Q2613A">HP LaserJet 1300</option>
<option value="HP LaserJet 1320:Q5949A">HP LaserJet 1320</option>
<option value="HP LaserJet 2015:Q7553A">HP LaserJet 2015</option>
<option value="HP LaserJet 4250:Q5942A">HP LaserJet 4250</option>
<option value="HP LaserJet 4350:Q5942A">HP LaserJet 4350</option>
<option value="HP LaserJet 1522:CB436A">HP LaserJet 1522</option>
<option value="HP LaserJet 1536:CE278A">HP LaserJet 1536</option>
<option value="HP LaserJet M426:CF226A">HP LaserJet M426</option>
<option value="HP LaserJet M436dn:CF256X">HP LaserJet M436dn</option>
<option value="HP LJ MFP M127fn:CF283A">HP LJ MFP M127fn</option>
<option value="HP LaserJet P2035:CE505A">HP LaserJet P2035</option>
<option value="HP LaserJet P2055:CE505A">HP LaserJet P2055</option>
<option value="HP LaserJet M251 Голубой:CF211A">HP LaserJet M251 Голубой</option>
<option value="HP LaserJet M251 Желтый:CF212A">HP LaserJet M251 Желтый</option>
<option value="HP LaserJet M251 Комплект:Комплект">HP LaserJet M251 Комплект</option>
<option value="HP LaserJet M251 Пурпурный:CF213A">HP LaserJet M251 Пурпурный</option>
<option value="HP LaserJet M251 Черный:CF210A">HP LaserJet M251 Черный</option>
<option value="HP LaserJet M401:CF280A">HP LaserJet M401</option>
<option value="HP LaserJet M402:CF226A">HP LaserJet M402</option>
<option value="HP LaserJet M425:CF280A">HP LaserJet M425</option>
<option value="HP LaserJet Pro M452 Желтый картридж:412X Желтый">HP LaserJet Pro M452 Желтый картридж</option>
<option value="HP LaserJet Pro M452 Голубой картридж:411X Голубой">HP LaserJet Pro M452 Голубой картридж</option>
<option value="HP LaserJet Pro M452 Черный картридж:410X Черный">HP LaserJet Pro M452 Черный картридж</option>
<option value="HP LaserJet Pro M452 Пурпурный картридж:413X Пурпурный">HP LaserJet Pro M452 Пурпурный картридж</option>
<option value="HP LaserJet 1214:CE285A">HP LaserJet 1214</option>
<option value="HP LaserJet M426:CF226A">HP LaserJet M426</option>
<option value="HP LaserJet Pro Color M177 Черный картридж:CF350A">HP LaserJet Pro Color M177 Черный картридж</option>
<option value="HP LaserJet Pro Color M177 Голубой картридж:CF352A">HP LaserJet Pro Color M177 Голубой картридж</option>
<option value="HP LaserJet Pro Color M177 Желтый картридж:CF351A">HP LaserJet Pro Color M177 Желтый картридж</option>
<option value="HP LaserJet Pro Color M177 Пурпурный картридж:CF353A">HP LaserJet Pro Color M177 Пурпурный картридж</option>
<option value="HP P1102:CF285A">HP P1102</option>
<option value="HP LaserJet M1120:CB436A">HP LaserJet M1120</option>
<option value="Konica Minolta bizhub 3602P:TNP58">Konica Minolta bizhub 3602P</option>
<option value="Kyocera FS-6525MFP:TK-475">Kyocera FS-6525MFP</option>
<option value="Kyocera P6235cdn:TK-5280K Черный">Kyocera P6235cdn Черный</option>
<option value="Kyocera P6235cdn:TK-5280C Голубой">Kyocera P6235cdn Голубой</option>
<option value="Kyocera P6235cdn:TK-5280Y Желтый">Kyocera P6235cdn Желтый</option>
<option value="Kyocera P6235cdn:TK-5280M Пурпурный">Kyocera P6235cdn Пурпурный</option>
<option value="Kyocera P3045dn:TK-3160">Kyocera P3045dn</option>
<option selected="selected" value="Kyocera P3055dn:TK-3190">Kyocera P3055dn</option>
<option value="Kyocera P3060dn:TK-3190">Kyocera P3060dn</option>
<option value="Kyocera P3155dn:TK-3160">Kyocera P3155dn</option>
<option value="Kyocera P5021:TK-5230 K Черный">Kyocera P5021 Черный</option>
<option value="Kyocera P5021:TK-5230 C Голубой">Kyocera P5021 Голубой</option>
<option value="Kyocera P5021:TK-5230 Y Желтый">Kyocera P5021 Желтый</option>
<option value="Kyocera P5021:TK-5230 M Пурпурный">Kyocera P5021 Пурпурный</option>
<option value="Kyocera M2235dn:TK-1200">Kyocera M2235dn</option>
<option value="Kyocera M2735dn:TK-1200">Kyocera M2735dn</option>
<option value="Kyocera M4125 A3: TK-6115">Kyocera M4125 A3</option>
<option value="Kyocera M2335dn:TK-1200">Kyocera M2335dn</option>
<option value="Kyocera P3150dn:TK-3160">Kyocera P3150dn</option>
<option value="Kyocera P3155dn:TK-3160">Kyocera P3155dn</option>
<option value="Kyocera P6235cdn:TK-5280K Черный">Kyocera P6235cdn Черный</option>
<option value="Kyocera P6235cdn:TK-5280C Голубой">Kyocera P6235cdn Голубой</option>
<option value="Kyocera P6235cdn:TK-5280Y Желтый">Kyocera P6235cdn Желтый</option>
<option value="Kyocera P6235cdn:TK-5280M Пурпурный">Kyocera P6235cdn Пурпурный</option>
<option value="Kyocera P7240:TK-5290 black Черный">Kyocera P7240 Черный</option>
<option value="Kyocera P7240:TK-5290 cyan Голубой">Kyocera P7240 Голубой</option>
<option value="Kyocera P7240:TK-5290 yellow Желтый">Kyocera P7240 Желтый</option>
<option value="Kyocera P7240:TK-5290 magenta Пурпурный">Kyocera P7240 Пурпурный</option>
<option value="Pantum BP5100:TL-5120X">Pantum BP5100</option>
<option value="Pantum M7100:TL-420X">Pantum M7100</option>
<option value="Xerox Phaser 3020:106R02773">Xerox Phaser 3020</option>
<option value="Xerox Phaser 3052:106R02782">Xerox Phaser 3052</option>
<option value="Xerox C7000 Чёрный:106R03769">Xerox C7000 Чёрный</option>
<option value="Xerox C7000 Желтый:106R03770">Xerox C7000 Желтый</option>
<option value="Xerox C7000 Пурпурный:106R03771">Xerox C7000 Пурпурный</option>
<option value="Xerox C7000 Голубой:106R03772">Xerox C7000 Голубой</option>
                </select>
            </div>
            <div class="field">
                <label for="user_comment" class="lbl">Комментарии</label>
                <textarea id="user_comment" name="user_comment" class="it" maxlength="4096"></textarea>
            </div>
            <div class="field"><a href="#" class="btn profile_form_btn" id="submit_cartridge_change_form" style="width:180px">Отправить заявку</a></div>
        </form>
        <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
        @else
            <div class="news_li_date">Для отправки заявки на замену картриджа на портале, необходимо <a href="#" id="cartridge_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection