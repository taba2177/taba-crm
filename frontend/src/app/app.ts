import { Component, signal, inject, OnInit } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { Header } from './components/header/header';
import { Footer } from './components/footer/footer';
import { ApiService } from './services/api.service';
import { LucideAngularModule, MessageCircle } from 'lucide-angular';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, Header, Footer, LucideAngularModule],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App implements OnInit {
  protected readonly title = signal('frontend');
  private api = inject(ApiService);
  settings = signal<any>({});

  readonly WhatsAppIcon = MessageCircle; // Approximate whatsapp via message

  ngOnInit() {
    this.api.getNavigation().subscribe({
      next: (res: any) => {
        this.settings.set(res.settings || {});
      }
    });
  }
}
