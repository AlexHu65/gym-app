import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
  readonly apiBaseUrl = environment.apiBaseUrl;

  login(email: string, password: string): Promise<void> {
    console.log('Login request', { email, password, apiBaseUrl: this.apiBaseUrl });
    return Promise.resolve();
  }

  logout(): Promise<void> {
    console.log('Logout request');
    return Promise.resolve();
  }
}

