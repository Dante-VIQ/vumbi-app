# 🧠 Vumbi AI – Autonomous Marketing Intelligence Agent

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Built with Strands SDK](https://img.shields.io/badge/Built%20with-Strands%20SDK-blueviolet)](https://strandsagents.com)
[![Powered by Gemini](https://img.shields.io/badge/Powered%20by-Gemini-blue)](https://deepmind.google/technologies/gemini/)
[![Hackathon](https://img.shields.io/badge/Agents%20for%20Humans-2026-ff6b6b)](https://agentsforhumans.devpost.com)

> **An autonomous AI agent that handles marketing busywork so founders can focus on what matters.**

---

## 🎯 The Problem

Solo founders and small-business owners spend **30%+ of their time** on marketing busywork:
- Checking SEO performance across dozens of pages
- Fixing broken links and missing meta tags
- Following up with leads manually
- Creating content consistently
- Monitoring campaign performance

These tasks are **repetitive, judgment-heavy, and time-consuming** – exactly what AI agents should handle.

---

## 💡 The Solution

**Vumbi AI** is an autonomous marketing intelligence agent that:

1. **Monitors** – Continuously scans for SEO issues, lead activity, and campaign performance
2. **Decides** – Evaluates each opportunity against risk thresholds
3. **Executes** – Automatically fixes low-risk issues (broken links, simple updates)
4. **Learns** – Records outcomes and improves future decisions

**The "Human Gate"** – High-risk actions (meta tag changes, content publishing) require human approval. The agent only surfaces when real judgment is needed.

---

## 🏆 Hackathon Track

**Professional Agents** – Built for solo founders, marketers, and creators who need a marketing team but can't afford one.

---

## 🏗️ Architecture






**Tech Stack:**

| Component | Technology |
|-----------|------------|
| Agent Orchestration | [Strands Agents SDK](https://strandsagents.com) |
| AI Model | Google Gemini 2.5 Flash |
| Backend API | Laravel 13 (PHP) |
| Scheduler | Node.js `setInterval` |
| Language | TypeScript (ES Modules) |
| Data Validation | Zod |

---

## 🚀 Quick Start

### Prerequisites

- Node.js 20+
- PHP 8.2+ (for Laravel backend)
- Google Gemini API key ([Get one here](https://aistudio.google.com/app/apikey))
- Laravel backend running with API endpoints (see below)

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/vumbi-ai-agent.git
cd vumbi-ai-agent

### 2. Install Dependencies

```bash
npm install --legacy-peer-deps

### 3. Configure Environment

```bash
cp .env.example .env

###  Edit .env with your credentials:

```bash
# Gemini API
GEMINI_API_KEY=your_gemini_api_key_here
STRANDS_MODEL_ID=gemini-2.5-flash

# Laravel Backend
LARAVEL_API_URL=http://localhost:8000/api
LARAVEL_API_KEY=your_api_key_here

# Agent Settings
BRAND_ID=1
AGENT_INTERVAL=900          # Run every 15 minutes
AUTONOMOUS_THRESHOLD=0.8    # Minimum confidence for auto-execution
MAX_ACTIONS_PER_CYCLE=10

# Logging
LOG_LEVEL=info

### 4. Run The Agent

```bash
npm run build
npm start

 Tools

The agent exposes these tools to the Strands runtime:

Tool	Purpose
monitor_opportunities	Scan for SEO issues, pending leads, campaign problems
decide_on_opportunity	Evaluate risk and determine automation eligibility
execute_action	Execute approved actions (fix links, generate content, etc.)
generate_content	Create blog, social, or email content
record_learning	Log outcomes and improve future decisions


### Agent Workflow

```bash
Every 15 minutes:
1. MONITOR → Scan for opportunities
2. DECIDE → For each opportunity, check confidence
   ├── Confidence >= 0.8 → AUTO-EXECUTE
   └── Confidence < 0.8 → QUEUE FOR HUMAN REVIEW
3. EXECUTE → Fix issues, generate content, notify leads
4. LEARN → Record outcomes for future improvement

### Project Structure

vumbi-ai-agent/
├── src/
│   ├── agent.ts                    # Main agent definition
│   ├── index.ts                    # Entry point with scheduler
│   ├── agent/
│   │   └── tools/
│   │       ├── DecisionTool.ts     # Risk evaluation logic
│   │       ├── ExecuteTool.ts      # Action execution logic
│   │       ├── MonitorTool.ts      # Opportunity scanning logic
│   │       └── LearnTool.ts        # Learning and feedback logic
│   ├── services/
│   │   └── LaravelApiService.ts    # Laravel API client
│   ├── tools/
│   │   └── wrapped/
│   │       ├── monitorTool.ts      # Strands-wrapped monitor
│   │       ├── decisionTool.ts     # Strands-wrapped decision
│   │       ├── executeTool.ts      # Strands-wrapped execution
│   │       ├── generateContentTool.ts # Strands-wrapped content generator
│   │       └── learnTool.ts        # Strands-wrapped learning
│   └── types/
│       └── index.ts                # Type definitions
├── dist/                           # Compiled output
├── .env.example                    # Example environment variables
├── package.json
├── tsconfig.json
├── README.md
└── LICENSE


Laravel API Endpoints

The agent expects these endpoints in your Laravel backend:
Endpoint	Method	Purpose
/agent/opportunities/{brandId}	GET	Fetch current opportunities
/agent/analytics/{brandId}	GET	Fetch analytics data
/agent/seo/issues/{brandId}	GET	Fetch SEO issues
/agent/leads/pending/{brandId}	GET	Fetch pending leads
/agent/campaigns/{brandId}	GET	Fetch campaign data
/agent/scan/{brandId}	POST	Trigger a scan
/agent/content/generate	POST	Generate content
/agent/leads/notify	POST	Notify a lead
/agent/campaigns/pause	POST	Pause a campaign
/agent/actions/pending	POST	Create a pending action
/agent/learn/{brandId}	POST	Record learning data

All endpoints require the X-API-Key header matching LARAVEL_API_KEY.

Testing

Test the agent manually:

```bash
# Build
npm run build

# Run a single cycle
node -e "import('./dist/index.js').then(m => m.runCycle())"

# Check API connectivity
curl http://localhost:8000/api/agent/opportunities/1 \
  -H "X-API-Key: your_api_key_here"

  Monitoring

The agent logs all activity to the console:

```bash
[2026-08-29T08:31:20.708Z] 🔄 Running agent cycle for brand 1...
  ⏳ monitor_opportunities → ✓ Completed (19 opportunities)
  ⏳ decide_on_opportunity → ✓ Completed (8 auto-approved)
  ⏳ execute_action → ✓ Completed (3 actions executed)
  ⏳ record_learning → ✓ Completed
✅ Cycle completed
