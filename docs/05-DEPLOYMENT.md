# Deployment Guide

## Recommended Hosting

### Railway.app (Recommended)
- Support PHP 8.2+
- Auto-deploy dari GitHub
- MySQL/PostgreSQL support
- Free tier: $5/month credits
- Paid: $10-20/month

**Setup:**
1. Push code ke GitHub
2. Connect Railway ke GitHub repo
3. Add MySQL service
4. Set environment variables
5. Deploy otomatis

### Heroku
- Support PHP 8.2+
- Easy deployment
- Free tier limited
- Paid: $7/month+

### DigitalOcean + Forge
- Full control
- Professional setup
- Paid: $12/month (Forge) + $4-6/month (DigitalOcean)

---

## Pre-deployment Checklist

- [ ] All code committed to Git
- [ ] `.env` file NOT in Git
- [ ] `composer.json` updated
- [ ] `npm run build` successful
- [ ] Database migrations tested
- [ ] All tests passing
- [ ] APP_DEBUG=false
- [ ] APP_ENV=production

---

## Environment Variables

Set di hosting platform:

```env
APP_NAME=CoffeExpress
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_KEY=base64:xxxxx

DB_CONNECTION=mysql
DB_HOST=your-host
DB_PORT=3306
DB_DATABASE=coffe_express
DB_USERNAME=user
DB_PASSWORD=password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=xxxxx
PUSHER_APP_KEY=xxxxx
PUSHER_APP_SECRET=xxxxx
PUSHER_APP_CLUSTER=ap1

GOOGLE_MAPS_API_KEY=xxxxx
STORE_LATITUDE=xxxxx
STORE_LONGITUDE=xxxxx
```

---

## Post-deployment

1. Run migrations:
```bash
php artisan migrate --force
```

2. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

3. Test application:
- Check homepage loads
- Test login
- Test order creation
- Check real-time updates

---

## Monitoring

- Check application logs regularly
- Monitor database performance
- Setup error tracking (Sentry, etc)
- Monitor uptime
- Check API rate limits (Google Maps, Pusher)

---

## Backup

- Database backup daily
- Code backup (Git)
- File uploads backup (if applicable)

---

## Security

- Enable HTTPS (auto on most platforms)
- Use strong passwords
- Rotate API keys regularly
- Keep dependencies updated
- Monitor for vulnerabilities
