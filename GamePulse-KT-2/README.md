# 🎮 GamePulse — руководство по запуску

**GamePulse** — это веб-платформа для поиска, оценки и рекомендации видеоигр.  
Система анализирует ваши предпочтения, помогает вести игровой архив, добавлять друзей и получать рекомендации на основе их оценок.

Этот гайд поможет вам запустить проект на вашем компьютере с нуля.

---

## 📋 Что вам понадобится

- **Windows, macOS или Linux**
- **Локальный веб-сервер** (выберите один):
  - [OpenServer](https://ospanel.io/) (Windows — рекомендуется)
  - [XAMPP](https://www.apachefriends.org/) (Windows/macOS/Linux)
  - [MAMP](https://www.mamp.info/) (macOS)
- **Браузер** (Chrome, Firefox, Edge)

---

## 🚀 Пошаговая инструкция

### Шаг 1. Установите веб-сервер

<details>
<summary><b>Windows → OpenServer (рекомендуется)</b></summary>

1. Скачайте OpenServer с [официального сайта](https://ospanel.io/)
2. Установите в любую папку (например, `C:\OSPanel`)
3. Запустите `OpenServer.exe` (в трее появится красный флаг)
</details>

<details>
<summary><b>Windows/macOS/Linux → XAMPP</b></summary>

1. Скачайте XAMPP с [apachefriends.org](https://www.apachefriends.org/)
2. Установите, запустите панель управления
3. Нажмите **Start** напротив `Apache` и `MySQL`
</details>

<details>
<summary><b>macOS → MAMP</b></summary>

1. Скачайте MAMP с [mamp.info](https://www.mamp.info/)
2. Установите и запустите
3. Нажмите **Start Servers**
</details>

---

### Шаг 2. Скачайте проект

**Вариант А — через Git:**
```bash
git clone https://github.com/EGORKAXLL/GamePulse/tree/KT-2 
