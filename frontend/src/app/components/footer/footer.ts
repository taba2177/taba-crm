import { Component, inject, signal, OnInit } from '@angular/core';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { LucideAngularModule, Phone, Mail, MapPin, Facebook, Twitter, Instagram, Linkedin } from 'lucide-angular';
import { OptimizedImgDirective } from '../../directives/optimized-img.directive';

@Component({
  selector: 'app-footer',
  imports: [RouterLink, LucideAngularModule, OptimizedImgDirective],
  templateUrl: './footer.html',
  styleUrl: './footer.scss',
})
export class Footer implements OnInit {
  private api = inject(ApiService);
  categories = signal<any[]>([]);
  settings = signal<any>({});
  t = t;

  readonly PhoneIcon = Phone;
  readonly MailIcon = Mail;
  readonly MapPinIcon = MapPin;
  readonly FacebookIcon = Facebook;
  readonly TwitterIcon = Twitter;
  readonly InstagramIcon = Instagram;
  readonly LinkedinIcon = Linkedin;

  ngOnInit() {
    this.api.getNavigation().subscribe({
      next: (res: any) => {
        this.categories.set(res.categories || []);
        this.settings.set(res.settings || {});
      }
    });
  }
}
