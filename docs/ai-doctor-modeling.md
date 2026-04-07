# AI Doctor Modeling

This project treats "writing the model" as three editable layers:

1. Prompt behavior
   Edit `app/Services/AiDoctor/AiDoctorPromptBuilder.php`
2. Output contract
   Edit `app/Services/AiDoctor/AiDoctorResponseSchema.php`
3. Repeatable evaluation cases
   Edit `resources/ai-doctor/evals/baseline.php`

## Recommended workflow

1. Update the system prompt or context rules.
2. Keep the JSON schema stable unless the frontend also changes.
3. Add or update eval cases for the symptom patterns you care about.
4. Run:

```bash
php artisan ai-doctor:eval
```

Use a stable baseline without model credits:

```bash
php artisan ai-doctor:eval baseline --provider=fallback
```

When OpenAI billing and quota are available:

```bash
php artisan ai-doctor:eval baseline --provider=openai
```

## What to tune first

- Safety rules and escalation thresholds
- Follow-up questions for incomplete symptom reports
- Cost range realism
- Category mapping consistency

## What to avoid first

- Fine-tuning too early
- Changing the JSON shape without updating the frontend
- Judging quality by one happy-path prompt instead of a suite
