import { Component, Input } from '@angular/core';
import { LucideAngularModule, CheckCircle } from 'lucide-angular';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-about-section',
  standalone: true,
  imports: [LucideAngularModule, ScrollRevealDirective],
  template: `
    <section id="about" class="py-24 lg:py-36 bg-surface relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none grid grid-cols-4 gap-4 opacity-5">
        <div class="border-r border-wood-950 h-full"></div>
        <div class="border-r border-wood-950 h-full"></div>
        <div class="border-r border-wood-950 h-full"></div>
        <div class="border-r border-wood-950 h-full"></div>
      </div>

      <div class="max-w-[100rem] mx-auto px-6 sm:px-12 relative z-10">
        <div class="grid lg:grid-cols-2 gap-20 lg:gap-32 items-center">
          <div class="relative group" scrollReveal="fade-right">
            @if (section.posts && section.posts[0]) {
              <div class="aspect-[3/4] lg:aspect-square overflow-hidden bg-wood-200 relative">
                <img [src]="section.posts[0].image?.url || ''" alt="About Us"
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2s] ease-out mix-blend-multiply" />
                <div class="absolute inset-0 bg-wood-900 mix-blend-color opacity-20"></div>
              </div>
            }
            @if (settings?.crm_business_founded_year || settings?.crm_contact_city) {
              <div class="absolute -bottom-12 lg:-bottom-24 -left-6 lg:-left-12 bg-accent text-surface p-10 lg:p-16 shadow-2xl">
                @if (settings?.crm_business_founded_year) {
                  <div class="text-[4rem] lg:text-[5.5rem] font-display font-bold leading-none mb-2 text-surface">{{ settings.crm_business_founded_year }}</div>
                }
                <div class="font-sans text-surface/80 uppercase tracking-[0.2em] text-xs font-semibold">
                  {{ t(settings?.crm_business_founded_label) || '' }}{{ t(settings?.crm_contact_city) ? ' · ' + t(settings?.crm_contact_city) : '' }}
                </div>
              </div>
            }
          </div>

          <div class="pt-12 lg:pt-0 pr-0 lg:pr-12" scrollReveal="fade-left" [revealDelay]="200">
            <div class="flex items-center gap-6 mb-10">
              <span class="text-accent uppercase tracking-[0.2em] font-display font-bold text-sm">{{ t(section.name) }}</span>
              <span class="h-px bg-wood-300 flex-1"></span>
            </div>
            @if (section.posts && section.posts[0]) {
              <h2 class="text-3xl lg:text-5xl font-display font-extrabold text-wood-950 mb-10 leading-[1.1]">
                {{ t(section.posts[0]?.title) || t(section.subtitle) || '' }}
              </h2>
              <div class="text-base text-wood-600 font-sans leading-loose mb-14 text-justify">
                {{ t(section.posts[0]?.content) || t(section.description) || '' }}
              </div>
            }

            <div class="grid sm:grid-cols-2 gap-8">
              @for (post of section.posts?.slice(1); track post.id) {
                <div class="group border border-wood-200 hover:border-wood-900 p-8 transition-colors bg-surface">
                  <div class="w-14 h-14 bg-wood-100 flex items-center justify-center text-wood-900 mb-6 group-hover:bg-wood-900 group-hover:text-accent transition-colors">
                    <lucide-icon [img]="CheckCircleIcon" class="w-6 h-6"></lucide-icon>
                  </div>
                  <h4 class="font-display font-bold text-lg text-wood-900 mb-3">{{ t(post.title) }}</h4>
                  <p class="text-wood-600 text-sm leading-relaxed">{{ t(post.content) }}</p>
                </div>
              }
            </div>
          </div>
        </div>
      </div>
    </section>
  `,
})
export class AboutSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  readonly CheckCircleIcon = CheckCircle;
  t = t;
}
