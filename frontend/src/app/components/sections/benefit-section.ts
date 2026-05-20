import { Component, Input } from '@angular/core';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-benefit-section',
  standalone: true,
  imports: [ScrollRevealDirective],
  template: `
    <section class="py-20 bg-accent text-surface relative overflow-hidden">
      <div class="absolute inset-0 flex items-center whitespace-nowrap opacity-10 pointer-events-none overflow-hidden">
        <h2 class="text-[20rem] font-display font-black blur-sm">{{ t(settings?.crm_business_name) || '' }}</h2>
      </div>

      <div class="max-w-[100rem] mx-auto px-6 sm:px-12 relative z-10">
        <div class="flex items-center gap-6 mb-16" scrollReveal="fade-up">
          <span class="text-surface/80 uppercase tracking-[0.3em] font-display font-bold text-sm">{{ t(section.name) }}</span>
          <span class="h-px bg-surface/20 flex-1"></span>
        </div>

        @if (section.posts && section.posts.length > 0) {
          <div class="grid md:grid-cols-2 gap-12">
            @for (post of section.posts; track post.id) {
              <div class="group border border-surface/20 p-10 lg:p-14 hover:bg-surface/10 transition-all">
                <h3 class="text-2xl lg:text-3xl font-display font-bold text-surface mb-6 group-hover:-translate-y-1 transition-transform">{{ t(post.title) }}</h3>
                <p class="text-surface/80 font-sans leading-relaxed text-base">{{ t(post.content) }}</p>
              </div>
            }
          </div>
        } @else {
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-12">
            @for (stat of defaultStats; track $index) {
              <div class="text-center px-4" [class.border-b]="!$last || isSmall" [class.lg\:border-b-0]="true" [class.lg\:border-l]="!$last" [class.border-surface\/20]="true" [class.pb-8]="!$last" [class.lg\:pb-0]="true">
                <h3 class="text-5xl font-display font-black mb-4">{{ stat.value }}</h3>
                <p class="text-surface/80 font-bold uppercase text-sm">{{ stat.label }}</p>
              </div>
            }
          </div>
        }
      </div>
    </section>
  `,
})
export class BenefitSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;
  isSmall = false;

  defaultStats = [
    { value: '+100', label: 'مشروع منجز' },
    { value: '2018', label: 'سنة التأسيس' },
    { value: '100%', label: 'جودة فاخرة' },
    { value: '24/7', label: 'دعم واستشارة' },
  ];
}
