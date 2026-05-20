import { Component, Input } from '@angular/core';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { t } from '../../utils/i18n';

@Component({
  selector: 'crm-why-choose-us-section',
  standalone: true,
  imports: [ScrollRevealDirective],
  template: `
    <section class="py-24 lg:py-36 bg-wood-50">
      <div class="max-w-[100rem] mx-auto px-6 sm:px-12">
        <div class="flex flex-col lg:flex-row gap-24 items-start">
          <div class="lg:w-1/3 sticky top-32" scrollReveal="fade-right">
            <h2 class="text-3xl lg:text-5xl font-display font-black text-wood-950 mb-10 leading-tight">
              {{ t(section.name) }}
            </h2>
            <p class="text-wood-600 leading-relaxed font-sans text-base mb-12 border-r-4 border-accent pr-6">
              {{ t(section.description) || '' }}
            </p>
          </div>

          <div class="lg:w-2/3 flex flex-col gap-8">
            @for (post of section.posts; track post.id; let i = $index) {
              <div class="bg-surface p-10 lg:p-14 border border-wood-200 hover:shadow-2xl hover:border-wood-400 transition-all duration-500 group flex flex-col md:flex-row gap-8 items-start" scrollReveal="fade-left" [revealDelay]="i * 150">
                <div class="text-5xl font-serif text-wood-300 group-hover:text-accent transition-colors italic">{{ padIndex(i) }}</div>
                <div>
                  <h3 class="text-2xl font-display font-bold text-wood-950 mb-5">{{ t(post.title) }}</h3>
                  <p class="text-wood-600 text-base leading-relaxed">{{ t(post.content) }}</p>
                </div>
              </div>
            }
          </div>
        </div>
      </div>
    </section>
  `,
})
export class WhyChooseUsSection {
  @Input() section: any = {};
  @Input() settings: any = {};
  t = t;

  padIndex(i: number): string {
    return '0' + (i + 1);
  }
}
