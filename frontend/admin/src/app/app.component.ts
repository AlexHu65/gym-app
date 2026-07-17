import { Component } from '@angular/core';
import { RouterLink, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterLink, RouterOutlet],
  template: `
    <div class="shell">
      <aside class="sidebar">
        <div class="brand">Gym Admin</div>
        <a routerLink="/">Dashboard</a>
        <a routerLink="/exercises">Exercises</a>
        <a routerLink="/imports">Imports</a>
      </aside>
      <main class="content">
        <router-outlet></router-outlet>
      </main>
    </div>
  `,
  styles: [`
    :host { display: block; font-family: Inter, system-ui, sans-serif; }
    .shell { min-height: 100vh; display: grid; grid-template-columns: 240px 1fr; background: #f8fafc; }
    .sidebar { padding: 24px; background: #111827; color: white; display: flex; flex-direction: column; gap: 14px; }
    .brand { font-size: 1.25rem; font-weight: 800; margin-bottom: 12px; }
    .sidebar a { color: #cbd5e1; text-decoration: none; }
    .content { padding: 24px; }
  `],
})
export class AppComponent {}

