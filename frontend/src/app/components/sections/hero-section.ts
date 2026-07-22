import { Component, Input } from '@angular/core';
import { RouterLink } from '@angular/router';
import { t } from '../../utils/i18n';
import { OptimizedImgDirective } from '../../directives/optimized-img.directive';

@Component({
  selector: 'crm-hero-section',
  standalone: true,
  imports: [RouterLink, OptimizedImgDirective],
  template: `
    <section class="relative min-h-[100vh] bg-wood-950 text-surface overflow-hidden">
      @if (section.posts && section.posts[0]) {
        <div class="absolute inset-0 z-0">
          <img [src]="section.posts[0].image?.url || ''" [srcset]="section.posts[0].image?.srcset || null"
               [attr.width]="section.posts[0].image?.width" [attr.height]="section.posts[0].image?.height"
               alt="Hero Image" appImg priority
               class="w-full h-full object-cover select-none animate-scale-in" />
          <div class="absolute inset-0 bg-wood-950/70"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-wood-950 via-transparent to-wood-950/40"></div>
        </div>
      }
      <div class="absolute inset-0 z-[1] bg-noise opacity-[0.04] mix-blend-overlay pointer-events-none"></div>

      <div class="relative z-10 min-h-[100vh] flex flex-col">
        <div class="flex-1 flex items-center">
          <div class="w-full max-w-[100rem] mx-auto px-6 sm:px-12 pt-24">
            <div class="max-w-4xl mx-auto text-center">
              <div class="inline-flex items-center gap-4 mb-10">
                <span class="h-[1px] bg-accent/60 w-12"></span>
                <span class="text-accent/90 tracking-[0.35em] font-display font-bold text-xs sm:text-sm uppercase">
                  {{ t(settings?.crm_business_name) || '' }}
                </span>
                <span class="h-[1px] bg-accent/60 w-12"></span>
              </div>

              @if (section.posts && section.posts[0]) {
                <h1 class="text-4xl sm:text-5xl lg:text-[4.5rem] font-display font-extrabold leading-[1.05] mb-8 text-surface">
                  {{ t(section.posts[0].title) || t(settings?.crm_seo_default_title) || '' }}
                </h1>
                <p class="text-base lg:text-lg text-wood-300/90 leading-relaxed mb-14 font-sans font-light max-w-2xl mx-auto">
                  {{ t(settings?.crm_business_description) || t(settings?.crm_seo_default_description) || '' }}
                </p>
              }

              <div class="flex flex-col sm:flex-row gap-5 items-center justify-center">
                <a routerLink="/contact" class="group relative px-10 py-5 bg-accent text-surface text-center overflow-hidden inline-flex justify-center items-center gap-3 rounded-full hover:shadow-[0_0_60px_rgba(232,184,75,0.35)] transition-all duration-500">
                  <span class="relative z-10 font-display font-bold text-base tracking-wide">{{ t(settings?.crm_hero_cta_label) || 'تواصل معنا' }}</span>
                </a>
                @if (settings?.crm_contact_whatsapp) {
                  <a [href]="'https://wa.me/' + settings.crm_contact_whatsapp" target="_blank" rel="noopener noreferrer"
                     class="group inline-flex items-center gap-3 px-8 py-5 border border-surface/20 text-surface rounded-full hover:bg-surface/10 hover:border-surface/40 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="text-[#25D366]"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span class="font-display font-bold text-base">{{ t(settings?.crm_hero_whatsapp_label) || 'واتساب' }}</span>
                  </a>
                }
              </div>
            </div>
          </div>
        </div>

        <div class="w-full border-t border-surface/10">
          @if (settings?.crm_business_stats?.length) {
            <div class="max-w-[100rem] mx-auto px-6 sm:px-12 py-6 flex flex-wrap justify-center lg:justify-between gap-8 lg:gap-0">
              @for (stat of settings.crm_business_stats; track $index) {
                <div class="flex items-center gap-3 text-surface/60 text-sm font-display">
                  <span class="text-2xl font-bold" [class.text-accent]="$last" [class.text-surface]="!$last">{{ stat.value }}</span>
                  <span>{{ t(stat.label) }}</span>
                </div>
                @if (!$last) {
                  <div class="w-px h-8 bg-surface/10 hidden lg:block"></div>
                }
              }
            </div>
          }
        </div>
      </div>
    </section>
  `,
})
export class HeroSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;
}
