@extends('layouts.static', ['class' => '__order'])

@section('news')
<div class="reserve">

            <div class="reserve_h">
               <p>График работы:</p>
              <h1 class="h __h_m reserve_h_t">Вячеслав Борисов</h1>
              <div class="reserve_slide">
                  <a href="" class="reserve_slide_prev">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M9.7 0l1.4 1.4-8.3 8.3 8.3 8.3-1.4 1.4L0 9.7"/></svg></a>
                  <span class="reserve_slide_tx">Апрель</span>
                  <span class="reserve_slide_tx">2021</span>
                  <a href="" class="reserve_slide_next">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 11.1 19.4"><path d="M0 1.4L1.4 0l9.7 9.7-9.7 9.7L0 18l8.3-8.3"/></svg></a>
              </div>
              <a href="" class="">Скачать отчет</a>
              <a href="" class="">Выбрать даты</a>
            </div>
            <div class="reserve_table">
              <div class="reserve_table_column">


              </div>
          </div>
</div>
<!--modal-->
<div class="overlay __js-modal-order">
  <div class="modal-w">
    <div class="modal-cnt __form">
      <div class="modal_h"><a href="#" title="Закрыть" class="modal-close"></a></div>
          <div class="profile_form_h">
            <div class="h light_h __h_m">Забронировать время</div>
          </div>
      </div>
  </div>
</div>
<!--eo modal-->

<!-- change modal -->
<div class="overlay __js-modal-change-order">
  <div class="modal-w">
    <div class="modal-cnt __form">
    </div>
  </div>
</div>
<!-- eo change modal -->
@endsection
