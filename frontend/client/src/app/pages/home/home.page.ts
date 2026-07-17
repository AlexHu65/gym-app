import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  standalone: true,
  imports: [RouterLink],
  template: `
    <section class="hero">
      <p class="eyebrow">Training catalog</p>
      <h1>Exercise explorer and workout tracking.</h1>
      <p class="copy">
        PWA-first client built on the dataset of 1,324 exercises.
      </p>
      <div class="actions">
        <a routerLink="/exercises">Browse exercises</a>
        <a routerLink="/login" class="secondary">Admin login</a>
      </div>
    </section>
  `,
  styles: [`
    .hero { padding: 32px; border-radius: 24px; background: linear-gradient(135deg, #111827, #1f2937); color: white; box-shadow: 0 20px 60px rgba(17,24,39,.18); }
    .eyebrow { text-transform: uppercase; letter-spacing: .16em; font-size: 12px; color: #f59e0b; }
    h1 { font-size: clamp(2.5rem, 5vw, 4.5rem); line-height: 1; margin: 12px 0 16px; }
    .copy { max-width: 560px; color: #d1d5db; }
    .actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
    .actions a { text-decoration: none; padding: 12px 18px; border-radius: 999px; background: white; color: #111827; font-weight: 700; }
    .actions .secondary { background: transparent; color: white; border: 1px solid rgba(255,255,255,.22); }
  `],
})
export class HomePage {}

