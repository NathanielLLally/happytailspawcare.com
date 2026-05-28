import { NextRequest, NextResponse } from 'next/server'
import { google } from 'googleapis'

export const runtime = 'nodejs'

const SPREADSHEET_ID = '1YYVl5EGOy2WOk74l3DmtaugP5RgW_c6UQDtJkTE4itE'
const SHEET_GID = 748445865

function getAuth() {
  const email = process.env.GOOGLE_SERVICE_ACCOUNT_EMAIL
  const key = process.env.GOOGLE_PRIVATE_KEY?.replace(/\\n/g, '\n')
  if (!email || !key) throw new Error('Google service account credentials not configured.')
  return new google.auth.GoogleAuth({
    credentials: { client_email: email, private_key: key },
    scopes: ['https://www.googleapis.com/auth/spreadsheets'],
  })
}

async function getSheetName(sheets: ReturnType<typeof google.sheets>): Promise<string> {
  const meta = await sheets.spreadsheets.get({ spreadsheetId: SPREADSHEET_ID })
  const sheet = meta.data.sheets?.find((s) => s.properties?.sheetId === SHEET_GID)
  return sheet?.properties?.title ?? 'Sheet1'
}

export async function POST(req: NextRequest) {
  let body: any
  try {
    body = await req.json()
  } catch {
    return NextResponse.json({ message: 'Invalid JSON' }, { status: 400 })
  }

  const serviceType = typeof body.serviceType === 'string' ? body.serviceType : ''
  const speculationModel = typeof body.speculationModel === 'string' ? body.speculationModel : ''
  const leadsPerMonth = typeof body.leadsPerMonth === 'string' ? body.leadsPerMonth : ''
  const activeClients = typeof body.activeClients === 'number' ? body.activeClients : 0
  const sourcePage =
    body?.meta?.sourcePage && typeof body.meta.sourcePage === 'string'
      ? body.meta.sourcePage
      : '/your-business-model'

  try {
    const auth = getAuth()
    const sheets = google.sheets({ version: 'v4', auth })
    const sheetName = await getSheetName(sheets)

    // Column order matches sheet headers:
    // A: Pet Service Type|select-1
    // B: Speculation Model|radio-1
    // C: Leads|radio-2
    // D: Active Clients|number-1
    // E: Timestamp (no header, informational)
    await sheets.spreadsheets.values.append({
      spreadsheetId: SPREADSHEET_ID,
      range: `${sheetName}!A1`,
      valueInputOption: 'USER_ENTERED',
      requestBody: {
        values: [[
          serviceType,
          speculationModel,
          leadsPerMonth,
          activeClients,
          new Date().toISOString(),
        ]],
      },
    })
  } catch (err: any) {
    console.error('Sheets append failed:', err?.message)
    // Still return 200 so the client redirects — sheet write is best-effort
  }

  return NextResponse.json({ ok: true }, { status: 200 })
}
