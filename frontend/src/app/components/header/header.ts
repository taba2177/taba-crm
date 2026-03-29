import { Component, inject, signal, OnInit } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { LucideAngularModule, Phone, Menu, X } from 'lucide-angular';

@Component({
  selector: 'app-header',
  imports: [RouterLink, RouterLinkActive, LucideAngularModule],
  templateUrl: './header.html',
  styleUrl: './header.scss',
})
export class Header implements OnInit {
  private api = inject(ApiService);
  categories = signal<any[]>([]);
  settings = signal<any>({});
  mobileMenuOpen = signal(false);
  t = t;

  readonly PhoneIcon = Phone;
  readonly MenuIcon = Menu;
  readonly XIcon = X;

  ngOnInit() {
    this.api.getNavigation().subscribe({
      next: (res: any) => {
        const cats = (res.categories || []).filter((c: any) => c.slug !== 'contact');
        this.categories.set(cats);
        this.settings.set(res.settings || {});
      },
    });
  }

  toggleMobileMenu() {
    this.mobileMenuOpen.update(v => !v);
  }
}
