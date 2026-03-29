import { Component, inject, signal, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../services/api.service';
import { t } from '../../utils/i18n';
import { LucideAngularModule, Phone, Mail, MapPin, Clock, Send } from 'lucide-angular';

@Component({
  selector: 'app-contact',
  imports: [FormsModule, LucideAngularModule],
  templateUrl: './contact.html',
  styleUrl: './contact.scss',
})
export class Contact implements OnInit {
  private api = inject(ApiService);

  settings = signal<any>({});
  t = t;

  readonly PhoneIcon = Phone;
  readonly MailIcon = Mail;
  readonly MapPinIcon = MapPin;
  readonly ClockIcon = Clock;
  readonly SendIcon = Send;

  name = '';
  email = '';
  message = '';
  quiz = '';

  submitting = signal(false);
  success = signal(false);
  errorMsg = signal('');

  ngOnInit() {
    this.api.getNavigation().subscribe({
      next: (res: any) => {
        this.settings.set(res.settings || {});
      }
    });
  }

  submit() {
    this.errorMsg.set('');
    this.success.set(false);

    if (!this.name || !this.email || !this.message || !this.quiz) {
      this.errorMsg.set('يرجى ملء جميع الحقول');
      return;
    }

    this.submitting.set(true);
    this.api.submitContact({
      name: this.name,
      email: this.email,
      message: this.message,
      quiz: this.quiz,
    }).subscribe({
      next: () => {
        this.success.set(true);
        this.submitting.set(false);
        this.name = '';
        this.email = '';
        this.message = '';
        this.quiz = '';
      },
      error: (err) => {
        this.submitting.set(false);
        if (err.status === 422) {
          const errors = err.error?.errors;
          if (errors) {
            this.errorMsg.set(Object.values(errors).flat().join(', '));
          } else {
            this.errorMsg.set(err.error?.error || 'حدث خطأ، حاول مجدداً');
          }
        } else {
          this.errorMsg.set('حدث خطأ، حاول مجدداً');
        }
      },
    });
  }
}
