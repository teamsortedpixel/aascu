<div class="fr-page-builder-container {{ $block->classes }}">
  @if ($block->preview)
    <div fr-empty-block-inner-blocks-state style="padding: 20px 0px;">
      <h6 style="text-align: center; margin-bottom: 0;color:#FF4E00">Free Range Page Builder</h6>
      <span style="display: flex;flex-direction:column;align-items: center;justify-content: center;width: 100%;">
        <svg version="1.1" width="80px" height="80px" viewBox="0 0 80.0 80.0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><clipPath id="i0"><path d="M80,0 L80,80 L0,80 L0,0 L80,0 Z"></path></clipPath><clipPath id="i1"><path d="M4.98315993,0 C5.63969085,0.361469129 6.22855185,0.679527236 6.81263615,1.00916161 C7.91923839,1.63370155 9.02424838,2.26171437 10.1255432,2.8984094 C10.2553102,2.97336576 10.3651743,3.1021518 10.4686695,3.22341327 C10.5607537,3.3310726 10.637977,3.45783279 10.7101582,3.58488239 C11.5219319,5.01223689 13.5411493,7.09683427 13.5411493,7.09683427 C13.5411493,7.09683427 14.4075897,7.96534416 14.7517774,8.2544616 C14.8300623,8.32015694 14.9834474,8.3441777 15.0739393,8.30423957 C15.4985349,8.11612513 15.9464833,7.72774115 16.3591371,7.50692375 C16.4719203,7.44672713 16.585765,7.33877838 16.6502505,7.21867455 C17.3444642,5.92300017 18.2040049,4.78707836 19.2002123,3.78370489 C19.3756233,3.60687732 19.4353322,3.43873195 19.2925618,3.20749588 C19.1537721,2.98320559 19.1811055,2.78004201 19.3371444,2.56906443 C19.5133515,2.33175083 19.6659406,2.07186349 19.8060571,1.8562554 C20.0279083,1.97693805 20.2216301,2.16794656 20.4084521,2.15955376 C20.6005815,2.15116096 20.7860768,1.94770796 21.0052742,1.81023972 C21.0352614,2.18357452 21.0612677,2.51147248 21.0880703,2.8480526 C21.1451255,2.85702422 21.1865235,2.87872974 21.2159799,2.86570643 C21.6649897,2.66833096 21.8810026,2.94210982 22.0144848,3.37622007 C22.1423944,3.79325531 22.425281,3.95705959 22.7721226,4.11362868 C23.2038832,4.30839948 23.5926536,4.62703639 24,4.89213304 C23.9923041,4.92165255 23.9848738,4.95117205 23.9771779,4.98040213 C23.4607635,5.18385512 22.9475336,5.39830558 22.426608,5.58497299 C22.2058183,5.66398106 22.1450481,5.77192981 22.2121872,6.03037011 C22.2859606,6.31543583 22.3124979,6.61670834 22.3607957,6.91942789 C21.9874169,6.83405286 21.6434946,6.77038337 21.3085947,6.66909099 C21.2199604,6.64217615 21.1268148,6.51339011 21.0907241,6.40717779 C20.9965169,6.13021545 20.8574619,6.09982775 20.655779,6.24858286 C20.6478178,6.25437099 20.6393259,6.26044853 20.6303032,6.2630532 C20.1136235,6.42656806 19.8110992,6.83173762 19.5313968,7.34340888 C19.1174162,8.10078656 18.8199339,8.8859473 18.6983934,9.76458643 C18.5665034,10.7181819 17.981623,13.3650968 17.9237718,13.6290358 C17.8659207,13.8929749 17.8027621,13.9873215 17.6573381,14.2353431 C17.5121795,14.4833648 15.1787614,17.6364522 14.8144053,18.1223663 C15.3271045,18.562554 15.7522308,18.9541215 16.4015967,18.9483334 C16.715059,18.9455148 17.1865447,18.9703217 17.6708028,19.0056433 L18.0341217,19.0338386 C18.8166667,19.0980415 19.5370505,19.1787138 19.5719988,19.2024326 C21.9213393,19.7077369 22.4974624,20.7750693 22.7116179,21.0983368 C22.7278055,21.1223574 22.7315206,21.1327761 22.7628346,21.1900787 C22.4693328,21.2372519 21.4954167,20.7177667 21.238271,20.7524955 C21.0986853,20.7715964 21.1342452,21.5150825 21.1342452,21.5150825 L21.1220013,21.5050878 L21.0375415,21.4358493 C20.9220948,21.3409239 20.7316755,21.1832293 20.6281804,21.0913909 C20.3426398,20.8688371 20.0647951,20.5808773 19.7009698,20.8234002 C19.5101671,20.9504498 19.3750926,20.9388735 19.1986201,20.86739 C19.0221475,20.7959065 18.9064452,20.28192 18.6262121,20.1991497 C18.2644161,20.0922223 17.4278581,20.0636922 16.8380566,20.0604012 L16.5451552,20.0607896 C16.3719842,20.0622913 16.2487891,20.0658523 16.2102633,20.0689166 C15.5370139,20.1224569 14.8653568,20.1991497 14.1923728,20.2593463 C14.0915313,20.2686073 13.9837902,20.2506641 13.8858678,20.2185399 C13.4480036,20.0749941 13.0058935,19.9424458 12.5775827,19.7670653 C12.0733754,19.5604288 11.5744756,19.5132555 11.0445273,19.6240983 C10.3816274,19.7624348 9.70890877,19.8385488 9.04308981,19.9595208 C8.90987295,19.9835416 8.77479848,20.0671801 8.6628114,20.1589221 C7.56708943,21.0581092 6.46552926,21.9494822 5.3886487,22.8776098 C4.98103694,23.2289498 4.55299152,23.4381909 4.05064186,23.5171989 C3.2797355,23.6387498 2.35889385,23.8627507 1.58931435,23.9964568 C1.50227225,24.0117953 1.38073176,23.9761982 1.31518482,23.9116606 C0.912880506,23.5148837 0.329857694,22.7676353 0.329857694,22.7676353 L0.652550338,21.9014406 L1.32341136,22.6185907 C1.32341136,22.6185907 1.93270602,22.4533395 2.25858314,22.3520471 C2.31271906,22.3352614 2.36313979,22.2116848 2.37587766,22.1294932 C2.41647962,21.8667117 2.4385055,21.6001681 2.47565762,21.261562 C2.75695219,21.6126126 3.00295227,21.9075182 3.23515297,22.2157364 C3.34262874,22.3581246 3.44877763,22.4243989 3.60402039,22.3332357 C3.6125123,22.3283157 3.6220657,22.3260006 3.63108836,22.322817 C4.07611761,22.170589 4.58775528,22.1257309 4.94733466,21.8357452 C5.30771017,21.5448915 5.51788498,21.0115146 5.79068764,20.5817455 C6.11921847,20.0642861 6.44456485,19.544222 6.7871604,19.0001372 C6.69666848,18.9225762 6.61732217,18.8496456 6.53346455,18.7842397 C5.13760657,17.6931759 3.72290715,16.6310529 2.45150876,15.3495594 C2.33554109,15.2329285 2.22806532,15.0804111 2.16888732,14.9195009 C1.46750849,13.0146251 0.779398268,11.1036718 0.0774886941,9.19908549 C0.000530744479,8.98984435 0.00557281703,8.82227778 0.0817346499,8.61853538 C0.363559968,7.86231534 0.624420881,7.09596606 0.906511572,6.33945659 C0.974977608,6.15597268 0.961178253,6.02400316 0.859275314,5.86540821 C0.579572971,5.43100857 0.314466105,4.98445382 0,4.47191432 C0.43733345,4.5726279 0.802220282,4.65684529 1.21460874,4.75206013 C1.14295824,4.22649733 1.07714592,3.74231971 1.00788377,3.2329637 C1.39983856,3.26856073 1.73659594,3.34293828 2.02054423,3.67778199 C2.40321101,4.12867784 2.860182,4.50085502 3.22586495,4.96737883 C3.68602041,5.55487468 4.12282312,6.17565234 4.49991706,6.83405286 C4.80403366,7.36482499 5.16361305,7.51039663 5.720364,7.63860385 C5.98149029,7.71863113 6.41117059,7.89385959 6.86055809,8.08838037 L7.19896809,8.23670219 C7.93011764,8.56076398 8.61504439,8.88565789 8.61504439,8.88565789 C8.61504439,8.88565789 7.91350851,8.13835039 7.16458626,7.37462686 L6.88279683,7.08888856 L6.60423073,6.81017639 C6.23836519,6.44705097 5.90153742,6.12550227 5.67551609,5.93689171 C5.85490773,5.79305651 6.00378155,5.65906114 6.16512788,5.54908655 C6.32355511,5.44055899 6.34186579,5.33261024 6.28189167,5.12568436 C6.00537379,4.17353588 5.59033161,3.30039548 5.15379428,2.43709492 C5.11133472,2.35287753 5.07338648,2.26518726 5.03623436,2.17778639 C5.02906932,2.1612902 5.03517288,2.13755884 5.03517288,2.07186349 L5.86684947,2.07186349 C5.56618274,1.36657905 5.29178783,0.723227667 4.98315993,0 Z"></path></clipPath></defs><g clip-path="url(#i0)"><g transform="translate(20.0 28.0)"><g transform="translate(8.0 0.0)"><g clip-path="url(#i1)"><polygon points="0,0 24,0 24,24 0,24 0,0" stroke="none" fill="#FF4E00"></polygon></g></g><g transform="translate(0.0 8.0)"><path d="M0,4 L4,0" stroke="#FF4E00" stroke-width="1.97530864" fill="none" stroke-linecap="round" stroke-linejoin="round"></path><g transform="translate(0.0 4.0)"><path d="M0,0 L4,4" stroke="#FF4E00" stroke-width="1.97530864" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g></g><g transform="translate(36.0 8.0)"><g transform="translate(0.0 4.0)"><path d="M4,0 L0,4" stroke="#FF4E00" stroke-width="1.97530864" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g><path d="M4,4 L0,0" stroke="#FF4E00" stroke-width="1.97530864" fill="none" stroke-linecap="round" stroke-linejoin="round"></path></g></g></g></svg>
        <span style="color:#abb8c3;font-size:16px;"><strong>Click here to start adding blocks</strong></span>
      </span>      
    </div>
  @endif
  <InnerBlocks allowedBlocks='{{ $allowed_blocks }}'/>
  @isset($theme_css)
    {!! $theme_css !!}
  @endisset
</div>
