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
                    <option value="Canon 7161:C-EXV6">Canon 7161</option>
                    <option value="Canon FC-128:E-16 / E-30">Canon FC-128</option>
                    <option value="Canon FC-228:E-16 / E-30">Canon FC-228</option>
                    <option value="Canon iR1022A:C-EXV18">Canon iR1022A</option>
                    <option value="Canon iR1510:C-EXV-9Y">Canon iR1510</option>
                    <option value="Canon LaserBase MF3228:EP-27">Canon LaserBase MF3228</option>
                    <option value="Epson AcuLaser C3800 гол.:Epson S051126 гол.">Epson AcuLaser C3800 гол.</option>
                    <option value="Epson AcuLaser C3800 желт.:Epson S051124 желт.">Epson AcuLaser C3800 желт.</option>
                    <option value="Epson AcuLaser C3800 пурп.:Epson S051125 пурп.">Epson AcuLaser C3800 пурп.</option>
                    <option value="Epson AcuLaser C3800 черн.:Epson S051127 черн.">Epson AcuLaser C3800 черн.</option>
                    <option value="HP 1102:CF285A">HP 1102</option>
                    <option value="HP 127:283A">HP 127</option>
                    <option value="HP 177 Голубой картридж:CF352A">HP 177 Голубой картридж</option>
                    <option value="HP 177 Желтый картридж:CF351A">HP 177 Желтый картридж</option>
                    <option value="HP 177 Пурпурный картридж:CF353A">HP 177 Пурпурный картридж</option>
                    <option value="HP 177 Черный картридж:CF350A">HP 177 Черный картридж</option>
                    <option value="HP Color LaserJet Pro M377 гол.:HP CF411A гол.">HP Color LaserJet Pro M377 гол.</option>
                    <option value="HP Color LaserJet Pro M377 желт.:HP CF412A желт.">HP Color LaserJet Pro M377 желт.</option>
                    <option value="HP Color LaserJet Pro M377 пурп.:HP CF413A пурп.">HP Color LaserJet Pro M377 пурп.</option>
                    <option value="HP Color LaserJet Pro M377  черн.:HP CF410A черн.">HP Color LaserJet Pro M377  черн.</option>
                    <option value="HP LaseJet Pro M452 пурпурный картридж:413X(пурпурный)">HP LaseJet Pro M452 пурпурный картридж</option>
                    <option value="HP LaserJet 1120:CB436A">HP LaserJet 1120</option>
                    <option value="HP LaserJet 1132:CE285A">HP LaserJet 1132</option>
                    <option value="HP LaserJet 1200:C7115A">HP LaserJet 1200</option>
                    <option value="HP LaserJet 1214:CE285A">HP LaserJet 1214</option>
                    <option value="HP LaserJet 1220:C7115A">HP LaserJet 1220</option>
                    <option value="HP LaserJet 1300:Q2613A">HP LaserJet 1300</option>
                    <option value="HP LaserJet 1320:Q5949A">HP LaserJet 1320</option>
                    <option value="HP LaserJet 1522:CB436A">HP LaserJet 1522</option>
                    <option value="HP LaserJet 1536:CE278A">HP LaserJet 1536</option>
                    <option value="HP LaserJet 2015:Q7553A">HP LaserJet 2015</option>
                    <option value="HP LaserJet 2035:CE505A">HP LaserJet 2035</option>
                    <option value="HP LaserJet 2055:CE505A">HP LaserJet 2055</option>
                    <option value="HP LaserJet 2300:Q2610A">HP LaserJet 2300</option>
                    <option value="HP LaserJet 2727:Q7553A">HP LaserJet 2727</option>
                    <option value="HP LaserJet 3015:CE255A">HP LaserJet 3015</option>
                    <option value="HP LaserJet 4015:CC364A">HP LaserJet 4015</option>
                    <option value="HP LaserJet 4250:Q5942A">HP LaserJet 4250</option>
                    <option value="HP LaserJet 4300:Q1339A">HP LaserJet 4300</option>
                    <option value="HP LaserJet 4350:Q5942A">HP LaserJet 4350</option>
                    <option value="HP LaserJet 4515:CC364A">HP LaserJet 4515</option>
                    <option value="HP LaserJet Enterprise M604n:CF281A">HP LaserJet Enterprise M604n</option>
                    <option value="HP LaserJet M251 Голубой картридж:CF211A">HP LaserJet M251 Голубой картридж</option>
                    <option value="HP LaserJet M251 Желтый картридж:CF212A">HP LaserJet M251 Желтый картридж</option>
                    <option value="HP LaserJet M251 Комплект картриджей:Комплект">HP LaserJet M251 Комплект картриджей</option>
                    <option value="HP LaserJet M251 Пурпурный картридж:CF213A">HP LaserJet M251 Пурпурный картридж</option>
                    <option value="HP LaserJet M251 Черный картридж:CF210A">HP LaserJet M251 Черный картридж</option>
                    <option value="HP LaserJet M400:CF280A">HP LaserJet M400</option>
                    <option value="HP LaserJet M402:CF226A">HP LaserJet M402</option>
                    <option value="HP LaserJet M425:CF280A">HP LaserJet M425</option>
                    <option value="HP LaserJet M426:CF226A">HP LaserJet M426</option>
                    <option value="HP LaserJet M436dn:CF256X">HP LaserJet M436dn</option>
                    <option value="HP LaserJet M600:CE390A">HP LaserJet M600</option>
                    <option value="HP LaserJet M607:37A">HP LaserJet M607</option>
                    <option value="HP LaserJet M607:CF237A">HP LaserJet M607</option>
                    <option value="HP LaserJet M608:237A">HP LaserJet M608</option>
                    <option value="HP LaserJet Pro M452 желтый картридж:412X(желтый)">HP LaserJet Pro M452 желтый картридж</option>
                    <option value="HP LaserJet Pro M452 синий картридж:411X(синий)">HP LaserJet Pro M452 синий картридж</option>
                    <option value="HP LaserJet Pro M452 черный картридж:410X(черный)">HP LaserJet Pro M452 черный картридж</option>
                    <option value="Kyocera 6525:TK-475">Kyocera 6525</option>
                    <option value="Kyocera M2235dn:TK-1200">Kyocera M2235dn</option>
                    <option value="Kyocera M2335dn:TK-1200">Kyocera M2335dn</option>
                    <option value="Kyocera M2735dn:TK-1200">Kyocera M2735dn</option>
                    <option value="Kyocera M4125 A3: TK-6115">Kyocera M4125 A3</option>
                    <option value="Kyocera P3045dn:TK-3160">Kyocera P3045dn</option>
                    <option value="Kyocera P3055dn:TK-3190">Kyocera P3055dn</option>
                    <option value="Kyocera P3060dn:TK-3190">Kyocera P3060dn</option>
                    <option value="Kyocera P3155dn:TK-3160">Kyocera P3155dn</option>
                    <option value="Kyocera P5021:TK-5230 K">Kyocera P5021 K</option>
                    <option value="Kyocera P5021:TK-5230 C">Kyocera P5021 C</option>
                    <option value="Kyocera P5021:TK-5230 M">Kyocera P5021 M</option>
                    <option value="Kyocera P5021:TK-5230 Y">Kyocera P5021 Y</option>
                    <option value="Kyocera P6235cdn: TK-5280C гол.">Kyocera P6235cdn гол.</option>
                    <option value="Kyocera P6235cdn: TK-5280K черн.">Kyocera P6235cdn черн.</option>
                    <option value="Kyocera P6235cdn: TK-5280M пурп.">Kyocera P6235cdn пурп.</option>
                    <option value="Kyocera P6235cdn: TK-5280Y желт.">Kyocera P6235cdn желт.</option>
                    <option value="Kyocera TASKalfa 1800:TK-4105">Kyocera TASKalfa 1800</option>
                    <option value="Kyocera P7240:TK-5290 black">Kyocera P7240 black</option>
                    <option value="Kyocera P7240:TK-5290 cyan">Kyocera P7240 cyan</option>
                    <option value="Kyocera P7240:TK-5290 magenta">Kyocera P7240 magenta</option>
                    <option value="Kyocera P7240:TK-5290 yellow">Kyocera P7240 yellow</option>
                    <option value="Pantum BP5100:TL-5120X">Pantum BP5100</option>
                    <option value="Pantum M7100:TL-420X">Pantum M7100</option>
                    <option value="Sharp AR-5415QE:AR-168T">Sharp AR-5415QE</option>
                    <option value="Toshiba e-studio 166:T-1640E">Toshiba e-studio 166</option>
                    <option value="Xerox C7000 Голубой 106R03772">Xerox C7000 Голубой</option>
                    <option value="Xerox C7000 Жёлтый 106R03770">Xerox C7000 Жёлтый</option>
                    <option value="Xerox C7000 Пурпурный 106R03771">Xerox C7000 Пурпурный</option>
                    <option value="Xerox C7000 Чёрный 106R03769">Xerox C7000 Чёрный</option>
                    <option value="Xerox Phaser 3020 черн:106R02773">Xerox Phaser 3020 черн</option>
                    <option value="Xerox Phaser 3052: 106R02782">Xerox Phaser 3052</option>
                </select>
            </div>
            <div class="field">
                <label for="user_comment" class="lbl">Комментарии</label>
                <textarea id="user_comment" name="user_comment" class="it" maxlength="4096"></textarea>
            </div>
            <div class="field"><a href="#" class="btn profile_form_btn" id="submit_cartridge_change_form">Отправить заявку</a></div>
        </form>
        <div class="news_li_date">После отправки заявка поступит в сервисный отдел УКОТ и специалисты рассмотрят ее.<br/><br/>Статус вашей заявки вы сможете контролировать через <a href="/profile">ваш профиль</a></div>
        @else
            <div class="news_li_date">Для отправки заявки на замену картриджа на портале, необходимо <a href="#" id="cartridge_auth" class="__js_auth">авторизоваться</a></div>
        @endif
    </div>
@endsection