 <div class="{{ $block->classes }} module module-testimonials-slider requires-splidejs">
     <div class="container-fluid">
         <div class="splide has-slides" @if($block->preview) style="visibility:visible;" @endif>
             <div class="testimonials-slider">
                 <div class="splide__track">
                     <div class="splide__list">
                         @foreach ($testimonials as $testimonial)
                             <div class="splide__slide">
                                <div class="left-container">
                                    <div class="profile-image">
                                        @if(is_array($testimonial['testimonial_image']))
                                            <img loading="lazy" class="image-circle" src="{{ $testimonial['testimonial_image']['url'] ? $testimonial['testimonial_image']['url'] : '' }}">
                                        @endif
                                    </div>
                                </div>
                                 <div class="right-container">
                                     <div class="testimonial-content">{!! $testimonial['testimonial_content'] !!}</div>
                                     <div class="author-details">
                                         <div class="testimonial-name">
                                             <h3>{!! $testimonial['testimonial_name'] !!}</h3>
                                         </div>
                                         <div class="testimonial-title">{!! $testimonial['testimonial_title'] !!}</div>
                                         <div class="testimonial-organization">{!! $testimonial['testimonial_organization'] !!}</div>
                                     </div>
                                 </div>
                             </div>
                         @endforeach
                     </div>
                 </div>
                 <div class="splide__arrows splide__arrows--ltr">

                     <div class="splide__arrows arrow--left-side">
                         <button class="splide__arrow splide__arrow--prev">
                             <div class="badge"></div>
                         </button>
                     </div>

                     <div class="splide__arrows arrow--right-side">
                         <button class="splide__arrow splide__arrow--next">
                             <div class="badge"></div>
                         </button>
                     </div>

                 </div>

             </div>
         </div>
     </div>
 </div>
