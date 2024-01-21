@extends('layouts.static', ['class'=>''])

@section('news')
<div class="dinner_top h __h_m">Меню на {{ $date_menu }}</div>
@foreach($positions as $type_meal   =>  $items)
<div class="dinner_i">
    <div class="dinner_i_h">
        {{ $type_meal }}
        @if($type_meal  ==  "САЛАТЫ")<svg class="dinner_i_h_ic" viewBox="0 0 34 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M28.1802 2.09197C28.2134 2.42071 28.1911 2.8761 28.134 3.37286C28.0771 3.86768 27.991 4.3603 27.9182 4.73246C27.882 4.91765 27.8495 5.07103 27.8262 5.17747C27.8145 5.23066 27.8052 5.27201 27.7988 5.29964L27.7917 5.33053L27.79 5.33778L27.7897 5.33928L27.7735 5.3996C27.6331 5.92301 28.1151 6.40057 28.6372 6.25532L28.6611 6.24868L28.6623 6.24864L28.6679 6.24757L28.6925 6.24299C28.7146 6.2389 28.7479 6.23284 28.791 6.22526C28.8772 6.21009 29.0023 6.18888 29.1552 6.16525C29.462 6.11781 29.8753 6.06144 30.3074 6.02408C30.7272 5.9878 31.1432 5.97143 31.4887 5.99272C31.3614 6.0939 31.212 6.20276 31.0455 6.31575C30.6891 6.55757 30.3055 6.78454 30.0069 6.95295C29.8585 7.0366 29.7332 7.10462 29.6456 7.15143C29.6018 7.17482 29.5675 7.19286 29.5446 7.20486L29.5189 7.21822L29.5129 7.22132L29.5117 7.22195L29.459 7.24544C28.9275 7.48266 28.9008 8.22728 29.4141 8.50189L29.4534 8.52292L29.462 8.52905C29.4695 8.53394 29.4812 8.54161 29.4964 8.55188L29.502 8.55563C29.5381 8.58002 29.5915 8.61701 29.6562 8.66452C29.787 8.76061 29.9566 8.89442 30.1204 9.04929C30.2876 9.2075 30.4271 9.36753 30.5166 9.5133C30.5427 9.55573 30.5613 9.59177 30.5747 9.62177C30.5308 9.64641 30.4729 9.67501 30.3989 9.70586C30.0943 9.83271 29.6551 9.94045 29.1806 10.003C28.7071 10.0655 28.2519 10.076 27.9225 10.037C27.8942 10.0337 27.868 10.0301 27.8437 10.0264C27.2192 8.04391 25.5388 6.52977 23.462 6.14358C23.4609 5.74535 23.5779 5.23269 23.6838 4.76853L23.6838 4.7685L23.7044 4.67774C23.8083 4.22056 23.9771 3.52006 24.1841 2.94718C24.2277 2.82639 24.2704 2.71892 24.3114 2.62554C24.4054 2.76344 24.5042 2.92625 24.6021 3.10083C24.7477 3.36048 24.878 3.62216 24.9728 3.82093C25.0198 3.91973 25.0575 4.00165 25.0831 4.05829C25.0959 4.08659 25.1056 4.10849 25.112 4.12296L25.1191 4.13892L25.1504 4.22188C25.375 4.81643 26.2132 4.82441 26.4491 4.23424L26.4527 4.22892L26.4657 4.20799C26.4774 4.18909 26.4953 4.16056 26.5187 4.12385C26.5654 4.05037 26.634 3.94451 26.7194 3.81779C26.8912 3.5629 27.1267 3.23035 27.3871 2.90911C27.6526 2.58148 27.9202 2.29557 28.1541 2.11213L28.1802 2.09197ZM29.3767 11.4902C28.9506 11.5464 28.5068 11.5718 28.1027 11.5546C28.1046 11.6155 28.1056 11.6766 28.1056 11.738C28.1056 13.6864 26.6082 16.0136 24.4237 18.32C26.7119 21.7767 28.2894 24.9206 28.2894 26.1058C28.2894 28.2162 27.5093 29.0492 25.4389 29.0492C24.152 29.0492 20.5726 27.2411 16.7941 24.6575C13.0156 27.2411 9.43627 29.0491 8.14932 29.0491C6.07898 29.0491 5.29888 28.2162 5.29888 26.1057C5.29888 24.9205 6.87632 21.7767 9.16454 18.32C6.98001 16.0136 5.48267 13.6864 5.48267 11.738C5.48267 11.6736 5.48374 11.6095 5.48586 11.5456C5.04164 11.578 4.5368 11.5538 4.05469 11.4902C3.49995 11.417 2.92175 11.2847 2.45587 11.0906C2.22511 10.9945 1.98545 10.8685 1.78745 10.6999C1.59454 10.5357 1.36464 10.2637 1.32936 9.8756C1.28806 9.42135 1.45624 9.02209 1.63653 8.72847C1.80922 8.44723 2.03193 8.19914 2.23916 7.99891C2.01694 7.8657 1.77708 7.71543 1.54363 7.557C1.15433 7.29282 0.726613 6.96887 0.442191 6.63579C0.305969 6.47626 0.136118 6.23956 0.0756615 5.94628C0.0427975 5.78685 0.0401486 5.59913 0.104834 5.40525C0.170039 5.20981 0.288044 5.05506 0.424134 4.9399C0.635369 4.76115 0.88626 4.66485 1.09957 4.60768C1.32106 4.54833 1.56003 4.51618 1.79352 4.49942C2.26026 4.46592 2.78192 4.48898 3.25318 4.52972C3.50211 4.55123 3.74351 4.57829 3.96406 4.60645C3.9075 4.28549 3.85019 3.91802 3.80722 3.54429C3.74546 3.00729 3.7076 2.41179 3.76172 1.91293C3.78846 1.6665 3.84266 1.3907 3.96238 1.14499C4.08567 0.891974 4.31903 0.596706 4.72264 0.491403C5.04477 0.407358 5.3455 0.468967 5.57744 0.559851C5.80922 0.650668 6.02068 0.788915 6.203 0.931917C6.56727 1.21762 6.91739 1.60417 7.20958 1.96463C7.31896 2.09957 7.42361 2.2351 7.52148 2.36631C7.68549 2.07388 7.88385 1.75184 8.09459 1.49081C8.19954 1.36083 8.32509 1.22382 8.46861 1.1112C8.60105 1.00728 8.82273 0.865066 9.1121 0.844393C9.42192 0.822259 9.67061 0.945857 9.83667 1.07324C9.99777 1.19683 10.1196 1.35137 10.2101 1.48831C10.3917 1.76286 10.5394 2.10911 10.658 2.43748C10.8993 3.10521 11.0851 3.88512 11.1897 4.34537L11.2164 4.46193C11.3092 4.8639 11.4549 5.49528 11.4684 6.06061C13.0249 6.19588 14.8861 7.37169 16.7941 9.09851C18.6383 7.42942 20.4387 6.27511 21.9627 6.0776C21.9735 5.50796 22.1213 4.86791 22.215 4.46188L22.215 4.46186L22.2417 4.34531C22.3463 3.88506 22.5321 3.10515 22.7734 2.43742C22.892 2.10905 23.0397 1.7628 23.2213 1.48825C23.3118 1.35131 23.4336 1.19677 23.5947 1.07318C23.7608 0.945796 24.0095 0.822198 24.3193 0.844332C24.6087 0.865005 24.8303 1.00722 24.9628 1.11114C25.1063 1.22376 25.2319 1.36076 25.3368 1.49075C25.5475 1.75178 25.7459 2.07382 25.9099 2.36624C26.0078 2.23504 26.1124 2.09951 26.2218 1.96457C26.514 1.60411 26.8641 1.21756 27.2284 0.931856C27.4107 0.788854 27.6222 0.650607 27.854 0.559789C28.0859 0.468906 28.3866 0.407297 28.7088 0.491342C29.1124 0.596645 29.3457 0.891913 29.469 1.14493C29.5887 1.39063 29.6429 1.66644 29.6697 1.91287C29.7238 2.41173 29.6859 3.00723 29.6242 3.54423C29.5812 3.91796 29.5239 4.28543 29.4673 4.60639C29.6879 4.57823 29.9293 4.55117 30.1782 4.52965C30.6495 4.48892 31.1711 4.46585 31.6379 4.49936C31.8714 4.51612 32.1103 4.54827 32.3318 4.60762C32.5451 4.66478 32.796 4.76109 33.0073 4.93984C33.1434 5.055 33.2614 5.20975 33.3266 5.40519C33.3912 5.59907 33.3886 5.78679 33.3557 5.94622C33.2953 6.2395 33.1254 6.4762 32.9892 6.63573C32.7048 6.96881 32.2771 7.29276 31.8878 7.55694C31.6543 7.71537 31.4145 7.86564 31.1922 7.99885C31.3995 8.19908 31.6222 8.44717 31.7949 8.72841C31.9752 9.02203 32.1433 9.42129 32.102 9.87554C32.0668 10.2636 31.8369 10.5357 31.6439 10.6999C31.4459 10.8684 31.2063 10.9945 30.9755 11.0906C30.5096 11.2846 29.9314 11.417 29.3767 11.4902ZM18.2339 10.4934C19.9923 12.3042 21.7358 14.4761 23.2648 16.6301C23.4863 16.3885 23.6984 16.1478 23.8999 15.9086C25.4735 14.041 26.1056 12.6169 26.1056 11.738C26.1056 9.69977 24.4533 8.04749 22.4151 8.04749C22.1114 8.04749 21.6111 8.16168 20.8903 8.54565C20.1847 8.92148 19.3819 9.49476 18.5088 10.2512C18.4175 10.3304 18.3258 10.4111 18.2339 10.4934ZM10.6136 19.7566C9.95711 20.7631 9.36849 21.7336 8.87171 22.622C8.32848 23.5933 7.91119 24.4378 7.63596 25.1046C7.38408 25.7148 7.31891 26.019 7.30338 26.0914L7.30006 26.1064C7.29918 26.1098 7.29888 26.1095 7.29888 26.1057C7.29888 26.631 7.36191 26.8746 7.39512 26.9668C7.48394 26.9975 7.7016 27.0491 8.14932 27.0491C8.14521 27.0491 8.14884 27.0484 8.1607 27.0461C8.22506 27.0333 8.53181 26.9727 9.15928 26.719C9.82287 26.4506 10.6626 26.0431 11.6276 25.511C12.6796 24.931 13.8469 24.2214 15.0521 23.42C13.5049 22.2784 11.978 21.0338 10.6136 19.7566ZM7.3447 26.9454C7.34408 26.945 7.34381 26.9447 7.34386 26.9447C7.344 26.9446 7.34627 26.9458 7.35028 26.9485C7.34751 26.9471 7.34568 26.946 7.3447 26.9454ZM5.25116 2.09203C5.21795 2.42077 5.24027 2.87616 5.29739 3.37292C5.3543 3.86774 5.44037 4.36036 5.51316 4.73252C5.54937 4.91771 5.58191 5.07109 5.60524 5.17754C5.6169 5.23072 5.62624 5.27207 5.63256 5.29971L5.63968 5.33059L5.64138 5.33784L5.64173 5.33934L5.65791 5.39966C5.79829 5.92307 5.31627 6.40063 4.79418 6.25538L4.77031 6.24874L4.76912 6.2487L4.76348 6.24763L4.73892 6.24305C4.71681 6.23896 4.68352 6.2329 4.64043 6.22532C4.5542 6.21015 4.42908 6.18894 4.27622 6.16531C3.96936 6.11787 3.5561 6.0615 3.124 6.02414C2.70424 5.98786 2.28818 5.97149 1.94267 5.99278C2.07001 6.09396 2.21941 6.20282 2.38592 6.31582C2.74226 6.55764 3.12586 6.7846 3.42452 6.95301C3.57288 7.03666 3.69815 7.10468 3.78579 7.15149C3.82957 7.17488 3.86386 7.19292 3.88681 7.20492L3.91248 7.21828L3.91849 7.22138L3.91972 7.22201L3.97235 7.2455C4.50392 7.48272 4.53057 8.22735 4.01732 8.50195L3.978 8.52299L3.96939 8.52911C3.961 8.53458 3.94742 8.54353 3.92941 8.5557C3.8933 8.58008 3.83989 8.61707 3.77522 8.66458C3.6444 8.76067 3.47475 8.89448 3.31103 9.04935C3.14378 9.20756 3.0043 9.36759 2.91479 9.51336C2.88873 9.55579 2.87006 9.59183 2.85668 9.62183C2.90061 9.64647 2.95845 9.67507 3.03252 9.70592C3.33714 9.83277 3.77633 9.94051 4.25084 10.0031C4.72433 10.0655 5.17948 10.076 5.50893 10.0371C5.61283 10.0248 5.68972 10.0093 5.74306 9.99495C5.75448 9.97055 5.76651 9.9457 5.77911 9.92045C6.41252 8.03996 8.00173 6.59899 9.96909 6.17518C9.97673 5.77272 9.85635 5.24508 9.74764 4.76856L9.72696 4.6778C9.62305 4.22062 9.45432 3.52012 9.24731 2.94724C9.20366 2.82645 9.16097 2.71898 9.12004 2.6256C9.02599 2.7635 8.92719 2.92631 8.8293 3.10089C8.6837 3.36054 8.55335 3.62222 8.45864 3.82099C8.41156 3.91979 8.37392 4.00171 8.34831 4.05835C8.33551 4.08665 8.32575 4.10856 8.31935 4.12302L8.31232 4.13898L8.28098 4.22194C8.05635 4.81649 7.21817 4.82447 6.98226 4.2343L6.97865 4.22898L6.96571 4.20806C6.95395 4.18915 6.93609 4.16062 6.91273 4.12391C6.86596 4.05043 6.79741 3.94457 6.712 3.81785C6.5402 3.56296 6.30471 3.23041 6.04432 2.90918C5.77875 2.58154 5.51117 2.29563 5.27728 2.11219L5.25116 2.09203ZM26.1931 26.9669C26.2263 26.8747 26.2894 26.6311 26.2894 26.1058C26.2894 26.1125 26.2884 26.1081 26.2849 26.0915C26.2693 26.019 26.2042 25.7149 25.9523 25.1047C25.6771 24.4378 25.2598 23.5934 24.7166 22.622C23.6323 20.6832 22.1105 18.353 20.4003 16.1112C18.6841 13.8618 16.826 11.7645 15.0794 10.2513C14.2063 9.49482 13.4035 8.92154 12.698 8.54571C11.9771 8.16174 11.4769 8.04755 11.1731 8.04755C9.13495 8.04755 7.48267 9.69983 7.48267 11.738C7.48267 12.617 8.11473 14.0411 9.68832 15.9086C11.1868 17.6869 13.2645 19.5474 15.4965 21.2528C17.7206 22.952 20.0341 24.4488 21.9606 25.5111C22.9256 26.0432 23.7654 26.4507 24.429 26.719C25.0565 26.9728 25.3632 27.0334 25.4276 27.0461C25.4394 27.0485 25.443 27.0492 25.4389 27.0492C25.8867 27.0492 26.1043 26.9975 26.1931 26.9669ZM26.2444 26.9448C26.2445 26.9449 26.2425 26.9462 26.238 26.9486C26.242 26.9458 26.2443 26.9447 26.2444 26.9448ZM11.9645 17.3362C11.6911 17.0629 11.6911 16.6196 11.9645 16.3463L14.295 14.0158C14.5683 13.7424 15.0116 13.7424 15.2849 14.0158C15.5583 14.2891 15.5583 14.7323 15.2849 15.0057L12.9544 17.3362C12.681 17.6096 12.2378 17.6096 11.9645 17.3362ZM13.9466 18.3601C13.6733 18.6334 13.6733 19.0766 13.9466 19.35C14.22 19.6234 14.6632 19.6234 14.9366 19.35L17.2671 17.0195C17.5405 16.7461 17.5405 16.3029 17.2671 16.0296C16.9937 15.7562 16.5505 15.7562 16.2772 16.0296L13.9466 18.3601Z" fill="#F09235"/>@endif
        @if($type_meal  ==  "ГОРЯЧЕЕ")<svg class="dinner_i_h_ic" viewBox="0 0 39 30" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.3591 2.22787C13.3591 3.0903 12.953 3.87594 12.2727 4.32947C11.988 4.51923 11.8247 4.82438 11.8247 5.16662C11.8247 5.50886 11.988 5.81402 12.2727 6.00382C12.6021 6.22339 12.6911 6.66842 12.4715 6.9978C12.2521 7.32705 11.807 7.41626 11.4775 7.19659C11.143 6.97412 10.8688 6.67234 10.6792 6.31815C10.4897 5.96397 10.3907 5.56839 10.3911 5.16667C10.3907 4.76496 10.4897 4.36938 10.6792 4.0152C10.8688 3.66102 11.143 3.35925 11.4775 3.13679C11.7581 2.94971 11.9256 2.60996 11.9256 2.22792C11.9256 1.84587 11.7581 1.50612 11.4775 1.31909C11.1481 1.09951 11.0591 0.654489 11.2787 0.325104C11.4983 -0.00428024 11.9433 -0.0932565 12.2727 0.126317C12.953 0.5798 13.3591 1.36544 13.3591 2.22787ZM3.84594 24.7245L3.84529 24.7048H2.13599V23.7105H32.8542V24.7048H31.1449L31.1442 24.7245H3.84594ZM3.87872 25.7245L3.90871 26.6392C3.92627 27.1748 4.36741 27.6047 4.90814 27.6047H30.082C30.6227 27.6047 31.0639 27.1748 31.0814 26.6392L31.1114 25.7245H3.87872ZM1.13599 21.7105C0.583702 21.7105 0.135986 22.1582 0.135986 22.7105V25.7048C0.135986 26.2571 0.583704 26.7048 1.13599 26.7048H1.90978C1.96258 28.3153 3.28478 29.6047 4.90814 29.6047H30.082C31.7054 29.6047 33.0276 28.3153 33.0804 26.7048H33.8542C34.4065 26.7048 34.8542 26.2571 34.8542 25.7048V22.7105C34.8542 22.1582 34.4065 21.7105 33.8542 21.7105H29.3696C29.4984 21.5877 29.6273 21.4618 29.7563 21.3328C32.0173 19.0718 34.1124 14.3478 32.7483 11.9448L34.3317 10.3614C34.7611 10.9942 35.4863 11.4099 36.3087 11.4099C37.6273 11.4099 38.6964 10.3409 38.6964 9.02223C38.6964 7.70357 37.6273 6.63458 36.3087 6.63458C36.1726 6.63458 36.0392 6.64596 35.9094 6.66782L35.9097 6.6346C35.9097 5.53632 35.0193 4.646 33.9211 4.646C32.8228 4.646 31.9325 5.53632 31.9325 6.6346C31.9325 7.45908 32.4343 8.16636 33.149 8.46777L31.0422 10.5747C29.2513 9.89986 26.7096 10.3487 24.3988 11.7139C22.27 10.2307 19.4642 9.28998 16.5153 9.28998C10.1078 9.28998 3.95972 13.4283 3.95972 18.0307C3.95972 19.3855 4.37696 20.6295 5.16324 21.7105H1.13599ZM7.95117 21.7105H19.254C19.0719 19.4813 20.4146 16.3775 22.7194 14.1178C22.9492 13.8925 23.1868 13.6787 23.4304 13.4767L23.2555 13.3549C21.4606 12.1043 19.0534 11.29 16.5153 11.29C13.7173 11.29 10.9739 12.2011 8.96896 13.5817C6.9165 14.995 5.95972 16.6474 5.95972 18.0307C5.95972 19.3903 6.57451 20.6621 7.95117 21.7105ZM20.2581 21.7105H26.2701C26.9346 21.2253 27.6299 20.6307 28.342 19.9186C29.3237 18.937 30.3419 17.2717 30.8561 15.6414C31.1105 14.8352 31.2074 14.1431 31.1755 13.6209C31.1443 13.1081 31.0024 12.8926 30.9102 12.8004C30.6057 12.4959 29.9929 12.2174 28.9447 12.2714C27.9128 12.3246 26.6575 12.7024 25.4161 13.4358L24.3098 14.0895L24.2848 14.072C23.9871 14.306 23.6973 14.5595 23.4195 14.8319C21.9706 16.2523 20.9432 18.028 20.4921 19.6276C20.2604 20.4496 20.1977 21.1482 20.2581 21.7105ZM16.9768 4.32947C17.6571 3.87594 18.0632 3.0903 18.0632 2.22787C18.0632 1.36544 17.6571 0.5798 16.9768 0.126317C16.6474 -0.0932565 16.2024 -0.00428024 15.9828 0.325104C15.7632 0.654489 15.8522 1.09951 16.1816 1.31909C16.4622 1.50612 16.6297 1.84587 16.6297 2.22792C16.6297 2.60996 16.4622 2.94971 16.1816 3.13679C15.8471 3.35925 15.5729 3.66102 15.3833 4.0152C15.1938 4.36938 15.0948 4.76496 15.0952 5.16667C15.0948 5.56839 15.1938 5.96397 15.3833 6.31815C15.5729 6.67234 15.8471 6.97412 16.1816 7.19659C16.5111 7.41626 16.9562 7.32705 17.1756 6.9978C17.3952 6.66842 17.3062 6.22339 16.9768 6.00382C16.6921 5.81402 16.5288 5.50886 16.5288 5.16662C16.5288 4.82438 16.6921 4.51923 16.9768 4.32947ZM22.7673 2.22787C22.7673 3.0903 22.3612 3.87594 21.6809 4.32947C21.3962 4.51923 21.2329 4.82438 21.2329 5.16662C21.2329 5.50886 21.3962 5.81402 21.6809 6.00382C22.0103 6.22339 22.0993 6.66842 21.8797 6.9978C21.6603 7.32705 21.2152 7.41626 20.8857 7.19659C20.5512 6.97412 20.277 6.67234 20.0874 6.31815C19.8979 5.96397 19.7989 5.56839 19.7993 5.16667C19.7989 4.76496 19.8979 4.36938 20.0874 4.0152C20.277 3.66102 20.5512 3.35925 20.8857 3.13679C21.1663 2.94971 21.3338 2.60996 21.3338 2.22792C21.3338 1.84587 21.1663 1.50612 20.8857 1.31909C20.5563 1.09951 20.4673 0.654489 20.6869 0.325104C20.9065 -0.00428024 21.3515 -0.0932565 21.6809 0.126317C22.3612 0.5798 22.7673 1.36544 22.7673 2.22787Z" fill="#F09235"/>@endif
        @if($type_meal  ==  "ГАРНИРЫ")<svg class="dinner_i_h_ic" viewBox="0 0 39 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21.6924 6.05968C21.5861 5.92862 21.4656 5.80952 21.3333 5.70467C21.3685 5.54497 21.387 5.37905 21.387 5.20879C21.387 3.94007 20.3585 2.91156 19.0897 2.91156C17.9704 2.91156 17.0381 3.71211 16.834 4.7719C16.8068 4.91334 16.7925 5.0594 16.7925 5.20879C16.7925 5.41712 16.8202 5.61897 16.8722 5.81085C16.8795 5.83785 16.8873 5.86464 16.8956 5.89123C16.6998 5.98889 16.5199 6.11368 16.3608 6.26068C16.2072 6.31934 16.0615 6.39401 15.9258 6.48264C15.8759 6.51525 15.8273 6.54975 15.7801 6.58603C15.7468 6.5391 15.7117 6.49353 15.6749 6.4494C15.6347 6.40109 15.5924 6.35452 15.5483 6.30982C15.3464 6.10552 15.1051 5.94024 14.8368 5.82632C14.5709 5.71342 14.2784 5.65097 13.9713 5.65097C13.1447 5.65097 12.4238 6.10336 12.0427 6.77405C11.8593 7.09675 11.7546 7.46997 11.7546 7.86765C11.7546 8.07979 11.7844 8.28497 11.84 8.47922L11.8482 8.50696C11.7878 8.56662 11.7308 8.62972 11.6775 8.69595C11.646 8.7351 11.6158 8.77534 11.587 8.8166C11.5498 8.78874 11.5117 8.7621 11.4726 8.73675C11.4303 8.70929 11.3869 8.68335 11.3425 8.659C11.0503 8.49867 10.7147 8.4075 10.3578 8.4075C10.1643 8.4075 9.9771 8.4343 9.79964 8.48439C9.06553 8.69161 8.49859 9.2974 8.34729 10.0533C8.32111 10.1841 8.30737 10.3194 8.30737 10.4579C8.30737 10.8151 8.39871 11.151 8.5593 11.4435C7.89901 11.5442 7.31528 11.8777 6.89452 12.3576C6.62336 12.6669 6.41987 13.0371 6.30719 13.4449H33.0022C33.1071 13.1523 33.1643 12.837 33.1643 12.5084C33.1643 11.3831 32.4942 10.4144 31.5312 9.97938C31.1833 9.82222 30.7972 9.73474 30.3906 9.73474C30.088 9.73474 29.7967 9.7832 29.5241 9.87278C29.5511 9.73186 29.5653 9.58638 29.5653 9.43758C29.5653 8.86467 29.3556 8.34074 29.0087 7.93838C28.5874 7.44971 27.9639 7.14035 27.268 7.14035C27.2001 7.14035 27.1329 7.1433 27.0665 7.14907C26.4273 7.20462 25.8631 7.5219 25.4816 7.99315C25.161 7.39108 24.6792 6.88781 24.0939 6.54086C23.5406 6.21287 22.8947 6.02458 22.2048 6.02458C22.031 6.02458 21.8599 6.03654 21.6924 6.05968ZM35.0725 13.4449C35.1327 13.1421 35.1643 12.8289 35.1643 12.5084C35.1643 10.1643 33.4747 8.2148 31.2469 7.81133C30.6058 6.24432 29.0658 5.14035 27.268 5.14035C26.784 5.14035 26.3185 5.22069 25.8843 5.3684C25.1436 4.74359 24.2411 4.30308 23.2477 4.11966C22.7656 2.27376 21.0867 0.91156 19.0897 0.91156C17.2151 0.91156 15.6207 2.11202 15.0336 3.78616C14.6943 3.69798 14.3383 3.65097 13.9713 3.65097C12.1502 3.65097 10.5987 4.80538 10.0088 6.42233C8.01137 6.5928 6.42755 8.21287 6.3139 10.226C5.23694 10.9525 4.47262 12.1062 4.2643 13.4449H1.73737C1.13846 13.4449 0.652954 13.8926 0.652954 14.4449V17.4391C0.652954 17.9914 1.13846 18.4391 1.73737 18.4391H2.57648C2.63374 20.0497 4.06755 21.3391 5.82794 21.3391H33.1268C34.8872 21.3391 36.321 20.0497 36.3783 18.4391H37.2174C37.8163 18.4391 38.3018 17.9914 38.3018 17.4391V14.4449C38.3018 13.8926 37.8163 13.4449 37.2174 13.4449H35.0725ZM19.6832 9.67241C19.0199 9.6318 18.2017 9.99055 17.6499 11.1591L16.7456 10.7321C17.4426 9.25604 18.6079 8.6047 19.7443 8.67428C20.6556 8.73007 21.4909 9.253 21.9335 10.0674C23.6539 9.3682 25.4048 10.5188 25.7655 12.082L24.7911 12.3068C24.5276 11.1648 23.1615 10.4031 21.9492 11.1825C21.8157 11.2683 21.6495 11.2857 21.5012 11.2293C21.3529 11.1729 21.2401 11.0495 21.1973 10.8967C20.9994 10.1898 20.3771 9.71489 19.6832 9.67241ZM4.67607 16.4589L4.67537 16.4391H2.82178V15.4449H36.133V16.4391H34.2794L34.2787 16.4589H4.67607ZM4.71162 17.4589L4.74414 18.3736C4.76318 18.9092 5.24156 19.3391 5.82794 19.3391H33.1268C33.7132 19.3391 34.1916 18.9092 34.2106 18.3736L34.2431 17.4589H4.71162Z" fill="#F09235"/>@endif
        @if($type_meal  ==  "НАПИТКИ")<svg class="dinner_i_h_ic" viewBox="0 0 31 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.95946 5.68185L0.683472 1.76658L1.74207 0.0697021L8.95946 4.57227V7.47913H17.4463C17.684 4.19198 20.4262 1.599 23.7739 1.599C27.2778 1.599 30.1183 4.43947 30.1183 7.94336C30.1183 11.3091 27.4974 14.0627 24.1852 14.2746L23.1631 33.4389C23.1064 34.5006 22.2291 35.3324 21.1659 35.3324H7.05351C5.99717 35.3324 5.12297 34.5109 5.05737 33.4566L3.5071 8.54123C3.47128 7.96563 3.92845 7.47913 4.50517 7.47913H6.95946V5.68185ZM22.1114 7.47913H18.7506C18.8145 6.77928 19.0213 6.12075 19.3416 5.53299L22.1114 7.47913ZM24.4596 9.12906L24.255 12.9651C25.5595 12.8417 26.7193 12.2215 27.5432 11.2957L24.4596 9.12906ZM28.1312 10.4867L24.6436 8.0362L28.2062 5.53299C28.5965 6.24914 28.8183 7.07035 28.8183 7.94336C28.8183 8.87089 28.568 9.73995 28.1312 10.4867ZM24.2739 7.0738L27.643 4.70656C26.8182 3.72162 25.6242 3.05633 24.2739 2.92347V7.0738ZM23.2739 7.0738L19.9048 4.70656C20.7297 3.72162 21.9236 3.05633 23.2739 2.92347V7.0738ZM6.95946 15.5208V9.47913H5.56932L5.94525 15.5208H6.95946ZM8.95946 15.5208H22.1159L22.4381 9.47913H8.95946V15.5208ZM22.0625 16.5208H6.00747L7.05351 33.3324L21.1659 33.3324L22.0625 16.5208ZM11.4875 21.3129C11.4875 21.7744 11.1134 22.1485 10.6519 22.1485C10.1905 22.1485 9.81635 21.7744 9.81635 21.3129C9.81635 20.8514 10.1905 20.4773 10.6519 20.4773C11.1134 20.4773 11.4875 20.8514 11.4875 21.3129ZM11.0233 26.9145C11.4848 26.9145 11.8589 26.5404 11.8589 26.0789C11.8589 25.6174 11.4848 25.2433 11.0233 25.2433C10.5619 25.2433 10.1877 25.6174 10.1877 26.0789C10.1877 26.5404 10.5619 26.9145 11.0233 26.9145ZM14.8609 29.7927C14.8609 30.2542 14.4868 30.6283 14.0253 30.6283C13.5638 30.6283 13.1897 30.2542 13.1897 29.7927C13.1897 29.3312 13.5638 28.9571 14.0253 28.9571C14.4868 28.9571 14.8609 29.3312 14.8609 29.7927ZM17.4295 27.7501C17.891 27.7501 18.2651 27.376 18.2651 26.9145C18.2651 26.453 17.891 26.0789 17.4295 26.0789C16.968 26.0789 16.5939 26.453 16.5939 26.9145C16.5939 27.376 16.968 27.7501 17.4295 27.7501Z" fill="#F09235"/>@endif
        @if($type_meal  ==  "ЗАВТРАК")<svg class="dinner_i_h_ic" viewBox="0 0 43 35" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16.6124 3.51053C16.6124 4.50406 16.1445 5.40912 15.3609 5.93159C15.0329 6.15019 14.8447 6.50174 14.8447 6.896C14.8447 7.29027 15.0329 7.64181 15.3609 7.86047C15.7403 8.11342 15.8429 8.62609 15.5899 9.00555C15.3371 9.38484 14.8244 9.48761 14.4448 9.23455C14.0595 8.97826 13.7435 8.6306 13.5251 8.22258C13.3068 7.81456 13.1927 7.35884 13.1932 6.89606C13.1927 6.43328 13.3068 5.97757 13.5251 5.56955C13.7435 5.16154 14.0594 4.81389 14.4448 4.55762C14.768 4.3421 14.9609 3.9507 14.9609 3.51058C14.9609 3.07046 14.768 2.67906 14.4448 2.4636C14.0653 2.21065 13.9628 1.69797 14.2158 1.31852C14.4687 0.939063 14.9814 0.836562 15.3609 1.08951C16.1445 1.61193 16.6124 2.517 16.6124 3.51053ZM0.21167 21.8096C0.21167 18.0787 1.44502 15.0351 5.04268 14.8722C5.14333 14.8676 5.24583 14.8653 5.3502 14.8653L7.04268 14.9445V12.0034H27.7492V16.2733C28.5564 16.0422 29.3984 15.9157 30.2639 15.9157C36.2726 15.9157 41.1437 22.0098 41.1437 26.9143C41.1437 28.0159 40.8979 28.9185 40.4488 29.6561H41.2978C41.8501 29.6561 42.2978 30.1038 42.2978 30.6561V31.893C42.2978 33.5498 40.9547 34.893 39.2978 34.893H6.83642C5.17957 34.893 3.83643 33.5498 3.83643 31.893V30.6561C3.83643 30.1038 4.28414 29.6561 4.83643 29.6561H7.61113C7.46701 29.3216 7.34822 28.9737 7.25717 28.6147L5.3502 28.7538C1.515 28.7538 0.21167 25.6448 0.21167 21.8096ZM37.5668 29.6561C38.0789 29.3808 38.4244 29.0729 38.6548 28.7465C38.9315 28.3546 39.1437 27.7938 39.1437 26.9143C39.1437 25.5143 38.5963 23.8587 37.5841 22.3309L33.6335 26.2814C33.3992 26.5158 33.0193 26.5158 32.785 26.2814C32.5507 26.0471 32.5507 25.6672 32.785 25.4329L36.86 21.3579C36.7398 21.2134 36.6152 21.071 36.4863 20.9311C36.2141 20.6357 35.9282 20.3573 35.6304 20.098L31.8089 23.9195C31.5746 24.1538 31.1947 24.1538 30.9604 23.9195C30.7261 23.6852 30.7261 23.3053 30.9604 23.071L34.6725 19.3588C34.1835 19.027 33.6713 18.7451 33.1422 18.5209L30.66 21.0031C30.4257 21.2374 30.0458 21.2374 29.8115 21.0031C29.5772 20.7688 29.5772 20.3889 29.8115 20.1546L31.8638 18.1023C31.3392 17.9801 30.8041 17.9157 30.2639 17.9157C29.4055 17.9157 28.5599 18.0784 27.7492 18.3769V26.8884C27.7492 27.8714 27.5466 28.8072 27.1808 29.6561H37.5668ZM25.7492 26.8884C25.7492 27.9119 25.4417 28.8636 24.914 29.6561H9.87732C9.56926 29.1936 9.33624 28.6768 9.19578 28.123L8.78363 26.4981L5.28327 26.7534C4.05449 26.7375 3.43708 26.2813 3.02669 25.6409C2.51916 24.8488 2.21167 23.5593 2.21167 21.8096C2.21167 20.0598 2.51916 18.7703 3.02669 17.9782C3.43974 17.3336 4.0625 16.8757 5.30723 16.8655L9.04268 17.0402V14.0034H25.7492V26.8884ZM4.9749 19.8713C5.20784 19.5341 5.62117 19.2317 6.55464 19.2278L7.60365 19.2691V24.505L6.5442 24.5701C5.61809 24.564 5.20697 24.2626 4.9749 23.9266C4.68965 23.5136 4.53503 22.8446 4.53503 21.899C4.53503 20.9533 4.68965 20.2843 4.9749 19.8713ZM6.57333 18.2277L8.60365 18.3078V25.4454L6.57334 25.5702C4.16775 25.5702 3.53503 23.9266 3.53503 21.899C3.53503 19.8714 4.16774 18.2277 6.57333 18.2277ZM20.7799 5.93159C21.5636 5.40912 22.0315 4.50406 22.0315 3.51053C22.0315 2.517 21.5636 1.61193 20.7799 1.08951C20.4005 0.836562 19.8878 0.939063 19.6348 1.31852C19.3818 1.69797 19.4844 2.21065 19.8639 2.4636C20.187 2.67906 20.38 3.07046 20.38 3.51058C20.38 3.9507 20.187 4.3421 19.8639 4.55762C19.4785 4.81389 19.1626 5.16154 18.9442 5.56955C18.7258 5.97757 18.6118 6.43328 18.6123 6.89606C18.6118 7.35884 18.7258 7.81456 18.9442 8.22258C19.1626 8.6306 19.4785 8.97826 19.8639 9.23455C20.2435 9.48761 20.7561 9.38484 21.0089 9.00555C21.2619 8.62609 21.1594 8.11342 20.7799 7.86047C20.4519 7.64181 20.2638 7.29027 20.2638 6.896C20.2638 6.50174 20.4519 6.15019 20.7799 5.93159ZM5.83643 31.6561V31.893C5.83643 32.4453 6.28414 32.893 6.83642 32.893H39.2978C39.8501 32.893 40.2978 32.4453 40.2978 31.893V31.6561H5.83643Z" fill="#F09235"/>@endif
        @if($type_meal  ==  "СУП")<svg class="dinner_i_h_ic" viewBox="0 0 33 34" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.8074 3.47171C10.8074 4.46524 10.3395 5.3703 9.55581 5.89277C9.22782 6.11137 9.03966 6.46292 9.03966 6.85718C9.03966 7.25145 9.22782 7.60299 9.55581 7.82165C9.93526 8.0746 10.0378 8.58727 9.78481 8.96673C9.53203 9.34602 9.01935 9.44879 8.63973 9.19573C8.25439 8.93944 7.93846 8.59179 7.72009 8.18376C7.50172 7.77574 7.38769 7.32002 7.38818 6.85724C7.38769 6.39446 7.50171 5.93875 7.72008 5.53073C7.93845 5.12272 8.25439 4.77508 8.63973 4.5188C8.96292 4.30328 9.15587 3.91188 9.15587 3.47176C9.15587 3.03164 8.96292 2.64024 8.63973 2.42478C8.26028 2.17183 8.15772 1.65915 8.41073 1.2797C8.66368 0.900245 9.17635 0.797744 9.55581 1.0507C10.3395 1.57311 10.8074 2.47818 10.8074 3.47171ZM30.7449 17.1303V15.1836H2.83643V17.1303H30.7449ZM29.1248 18.1652H4.45649L4.46574 19.1336C4.49959 22.678 6.02733 25.8628 8.45586 28.0934L9.05564 28.6442H24.5257L25.1255 28.0934C27.554 25.8628 29.0817 22.678 29.1156 19.1336L29.1248 18.1652ZM9.10297 31.3386V29.6442H24.4783V31.3386H16.7907H9.10297ZM31.1155 19.1527H31.7449C32.2972 19.1527 32.7449 18.705 32.7449 18.1527V14.1836C32.7449 13.6313 32.2972 13.1836 31.7449 13.1836H28.5779L32.0819 8.70222C33.0826 7.42229 32.8241 5.56785 31.5113 4.61052C30.1843 3.64278 28.3176 3.98217 27.4161 5.35506L22.2759 13.1836H1.83643C1.28414 13.1836 0.836426 13.6313 0.836426 14.1836V18.1527C0.836426 18.705 1.28414 19.1527 1.83643 19.1527H2.46583C2.50518 23.2729 4.28388 26.9771 7.10297 29.5663V31.3386C7.10297 32.4432 7.9984 33.3386 9.10297 33.3386H16.7907H24.4783C25.5829 33.3386 26.4783 32.4432 26.4783 31.3386V29.5663C29.2974 26.9771 31.0761 23.2729 31.1155 19.1527ZM24.0703 13.1836H26.6738L30.9002 7.77828C31.3786 7.16648 31.255 6.28007 30.6275 5.82247C29.9932 5.3599 29.1009 5.52212 28.67 6.17836L24.0703 13.1836ZM14.975 5.89277C15.7587 5.3703 16.2265 4.46524 16.2265 3.47171C16.2265 2.47818 15.7587 1.57311 14.975 1.0507C14.5955 0.797744 14.0829 0.900245 13.8299 1.2797C13.5769 1.65915 13.6795 2.17183 14.0589 2.42478C14.3821 2.64024 14.5751 3.03164 14.5751 3.47176C14.5751 3.91188 14.3821 4.30328 14.0589 4.5188C13.6736 4.77508 13.3576 5.12272 13.1393 5.53073C12.9209 5.93875 12.8069 6.39446 12.8074 6.85724C12.8069 7.32002 12.9209 7.77574 13.1393 8.18376C13.3576 8.59179 13.6736 8.93944 14.0589 9.19573C14.4385 9.44879 14.9512 9.34602 15.204 8.96673C15.457 8.58727 15.3545 8.0746 14.975 7.82165C14.647 7.60299 14.4589 7.25145 14.4589 6.85718C14.4589 6.46292 14.647 6.11137 14.975 5.89277ZM21.6457 3.47171C21.6457 4.46524 21.1779 5.3703 20.3942 5.89277C20.0662 6.11137 19.878 6.46292 19.878 6.85718C19.878 7.25145 20.0662 7.60299 20.3942 7.82165C20.7736 8.0746 20.8762 8.58727 20.6232 8.96673C20.3704 9.34602 19.8577 9.44879 19.4781 9.19573C19.0928 8.93944 18.7768 8.59179 18.5585 8.18376C18.3401 7.77574 18.2261 7.32002 18.2266 6.85724C18.2261 6.39446 18.3401 5.93875 18.5585 5.53073C18.7768 5.12272 19.0928 4.77508 19.4781 4.5188C19.8013 4.30328 19.9943 3.91188 19.9943 3.47176C19.9943 3.03164 19.8013 2.64024 19.4781 2.42478C19.0987 2.17183 18.9961 1.65915 19.2491 1.2797C19.5021 0.900245 20.0147 0.797744 20.3942 1.0507C21.1779 1.57311 21.6457 2.47818 21.6457 3.47171Z" fill="#F09235"/>@endif
    </div>
    <ul class="dinner_lst">
        @foreach($items as $item)
        <li class="dinner_lst_i">
            <div class="dinner_lst_i_name"><span class="dinner_lst_i_bg">{{ $item->meals }} </span></div>
            <!--<div class="dinner_lst_i_mass"><span class="dinner_lst_i_bg">100 гр.</span></div>-->
            <div class="dinner_lst_i_price"><span class="dinner_lst_i_bg">{{ $item->price_meals }} ₽</span></div>
        </li>
        @endforeach
    </ul>
</div>
@endforeach
@endsection



