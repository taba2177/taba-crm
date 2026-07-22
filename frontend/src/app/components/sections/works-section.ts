import { Component, Input, AfterViewInit, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { OptimizedImgDirective } from '../../directives/optimized-img.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-works-section',
  standalone: true,
  imports: [RouterLink, ScrollRevealDirective, OptimizedImgDirective],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
  styles: `
    :host { display: block; }
    swiper-container#works-swiper {
      --swiper-pagination-color: #E8B84B;
      --swiper-pagination-bullet-inactive-color: #3A3F4D;
      --swiper-pagination-bullet-inactive-opacity: 0.5;
      --swiper-navigation-color: #E8B84B;
      --swiper-navigation-size: 24px;
    }
    swiper-container#works-swiper::part(bullet-active) { width: 24px; border-radius: 4px; }
    swiper-container#works-swiper swiper-slide { transition: transform 0.6s cubic-bezier(0.19, 1, 0.22, 1); }
  `,
  template: `
    <section class="py-24 lg:py-36 bg-wood-950 text-surface relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none opacity-[0.03]">
        <div class="absolute top-0 left-0 w-96 h-96 border border-accent/20 rotate-45 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-[40rem] h-[40rem] border border-accent/20 rotate-12 translate-x-1/4 translate-y-1/4"></div>
      </div>
      <div class="absolute inset-0 bg-noise opacity-[0.04] mix-blend-overlay pointer-events-none"></div>

      <div class="max-w-[100rem] mx-auto px-6 sm:px-12 relative z-10">
        <div class="text-center mb-20" scrollReveal="fade-up">
          <div class="inline-flex items-center gap-4 mb-8">
            <span class="h-[1px] bg-accent/60 w-16"></span>
            <span class="text-accent tracking-[0.35em] font-display font-bold text-xs sm:text-sm uppercase">{{ t(section.name) }}</span>
            <span class="h-[1px] bg-accent/60 w-16"></span>
          </div>
          <h2 class="text-3xl lg:text-5xl font-display font-extrabold text-surface leading-[1.1] mb-6">
            {{ t(section.name) }}
          </h2>
          <p class="text-wood-400 text-base max-w-2xl mx-auto font-sans leading-relaxed">
            {{ t(section.description) || '' }}
          </p>
        </div>

        @if (section.posts && section.posts[0]?.images?.length) {
          <div class="relative" scrollReveal="zoom-in" [revealDelay]="200">
            <swiper-container id="works-swiper" init="false"
              class="works-swiper-container !overflow-visible !pb-16">
              @for (img of section.posts[0].images; track img.id; let i = $index) {
                <swiper-slide class="!w-[340px] sm:!w-[420px] lg:!w-[500px]">
                  <div class="group relative overflow-hidden bg-wood-900/50 border border-wood-800/50 hover:border-accent/40 transition-all duration-700 shadow-2xl">
                    <div class="overflow-hidden">
                      <img [src]="img.url" [srcset]="img.srcset || null"
                           [attr.width]="img.width" [attr.height]="img.height"
                           [alt]="(i + 1).toString()" appImg sizes="(max-width: 640px) 90vw, 500px"
                           class="w-full h-full object-cover transition-transform duration-[2s] ease-out group-hover:scale-110" loading="lazy" />
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-wood-950/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute top-4 left-4 w-10 h-10 bg-accent/90 flex items-center justify-center text-surface font-display font-bold text-sm">
                      {{ (i + 1) < 10 ? '0' + (i + 1) : (i + 1) }}
                    </div>
                  </div>
                </swiper-slide>
              }
            </swiper-container>

            <div class="flex justify-center gap-4 mt-10">
              <button (click)="slidePrev()"
                class="w-14 h-14 border border-wood-700 hover:border-accent flex items-center justify-center text-wood-400 hover:text-accent transition-all duration-300 hover:shadow-[0_0_20px_rgba(232,184,75,0.2)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </button>
              <button (click)="slideNext()"
                class="w-14 h-14 border border-wood-700 hover:border-accent flex items-center justify-center text-wood-400 hover:text-accent transition-all duration-300 hover:shadow-[0_0_20px_rgba(232,184,75,0.2)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
              </button>
            </div>
          </div>
        }

        <div class="text-center mt-16" scrollReveal="fade-up" [revealDelay]="400">
          @if (section.slug) {
            <a [routerLink]="['/', section.slug]"
              class="group inline-flex items-center gap-4 px-10 py-5 border-2 border-accent/50 hover:border-accent text-accent rounded-full hover:bg-accent hover:text-surface transition-all duration-500 font-display font-bold text-base tracking-wide">
              <span>{{ t(settings?.crm_browse_all_label) || 'تصفح الكل' }}</span>
              <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
          }
        </div>
      </div>
    </section>
  `,
})
export class WorksSection implements AfterViewInit {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;

  ngAfterViewInit() {
    // Load Swiper only when this section is actually rendered, so its ~140kB
    // bundle stays out of the initial JavaScript payload (PageSpeed: reduce
    // unused JavaScript). `register()` is idempotent across sections.
    setTimeout(async () => {
      const { register } = await import('swiper/element/bundle');
      register();
      this.initSwiper();
    }, 300);
  }

  private initSwiper() {
    const swiperEl = document.querySelector('swiper-container#works-swiper') as any;
    if (swiperEl && !swiperEl.swiper) {
      Object.assign(swiperEl, {
        effect: 'coverflow',
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: 3,
        loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        coverflowEffect: { rotate: 8, stretch: 0, depth: 200, modifier: 1.5, slideShadows: true },
        pagination: { clickable: true },
        breakpoints: {
          320: { slidesPerView: 1.2 },
          640: { slidesPerView: 1.8 },
          1024: { slidesPerView: 2.5 },
          1440: { slidesPerView: 3 },
        },
      });
      swiperEl.initialize();
    }
  }

  slidePrev() {
    (document.querySelector('#works-swiper') as any)?.swiper?.slidePrev();
  }

  slideNext() {
    (document.querySelector('#works-swiper') as any)?.swiper?.slideNext();
  }
}
