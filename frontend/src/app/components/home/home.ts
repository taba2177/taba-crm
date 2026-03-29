import { Component, inject, signal, OnInit, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { LucideAngularModule, ArrowLeft, ArrowRight, ShieldCheck, PenTool, CheckCircle, ChevronRight, Phone, MessageCircle } from 'lucide-angular';
import { RouterLink } from '@angular/router';
import { ScrollRevealDirective } from '../../directives/scroll-reveal.directive';
import { register } from 'swiper/element/bundle';

register();

@Component({
  selector: 'app-home',
  imports: [CommonModule, LucideAngularModule, RouterLink, ScrollRevealDirective],
  templateUrl: './home.html',
  styleUrl: './home.scss',
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class Home implements OnInit {
  private api = inject(ApiService);
  public data = signal<any>(null);
  public settings = signal<any>({});
  public t = t;

  // Icons
  readonly ArrowLeftIcon = ArrowLeft;
  readonly ArrowRightIcon = ArrowRight;
  readonly ShieldCheckIcon = ShieldCheck;
  readonly PenToolIcon = PenTool;
  readonly CheckCircleIcon = CheckCircle;
  readonly ChevronRightIcon = ChevronRight;
  readonly PhoneIcon = Phone;
  readonly WhatsAppIcon = MessageCircle;

  ngOnInit() {
    this.api.getHome().subscribe({
      next: (response: any) => {
        this.data.set(response);
        if (response?.metaTitle) {
          document.title = response.metaTitle;
        }
        // Init Swiper after Angular renders the template
        setTimeout(() => this.initSwiper(), 500);
      },
      error: (err) => {
        console.error('Error fetching home data:', err);
      }
    });

    this.api.getNavigation().subscribe({
      next: (res: any) => {
        this.settings.set(res.settings || {});
      }
    });
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
        coverflowEffect: {
          rotate: 8,
          stretch: 0,
          depth: 200,
          modifier: 1.5,
          slideShadows: true,
        },
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

  getSectionData(componentName: string): any {
    if (!this.data()?.sections) return null;
    return this.data().sections.find((s: any) => s.section_component === componentName) || null;
  }
}
