"""Create the four-page Produce a Value commercial proposal PDF."""
from pathlib import Path
from reportlab.lib import colors
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle
from reportlab.pdfbase.pdfmetrics import stringWidth
from reportlab.pdfgen import canvas
from reportlab.platypus import Paragraph

ROOT = Path(__file__).resolve().parents[2]
OUT = ROOT / 'output/pdf/One-Hundred-CRO-Trail-Commercial-Proposal.pdf'
OUT.parent.mkdir(parents=True, exist_ok=True)
W, H = A4
M = 49
RIGHT = W-M
INK = colors.HexColor('#111111')
PAPER = colors.HexColor('#f5efe4')
ORANGE = colors.HexColor('#ff5a36')
VIOLET = colors.HexColor('#8b7cff')
YELLOW = colors.HexColor('#ffe600')
BODY = ParagraphStyle('body', fontName='Helvetica', fontSize=8.4, leading=12.2, textColor=INK, spaceAfter=0, alignment=TA_LEFT)
SMALL = ParagraphStyle('small', parent=BODY, fontSize=7.6, leading=10.5)
WHITE = ParagraphStyle('white', parent=BODY, textColor=PAPER, fontName='Helvetica-Bold')
WHITE_SMALL = ParagraphStyle('white-small', parent=SMALL, textColor=PAPER)

c = canvas.Canvas(str(OUT), pagesize=A4)
c.setTitle('One Hundred / CRO Trail - Commercial proposal')
c.setAuthor('Produce a Value - Tommaso Giovannoni')


def text(x, y, value, size=8, bold=False, color=INK):
    c.setFillColor(color)
    c.setFont('Helvetica-Bold' if bold else 'Helvetica', size)
    c.drawString(x, y, value)


def para(x, top, content, width=RIGHT-M, style=BODY):
    p = Paragraph(content, style)
    _, height = p.wrap(width, H)
    p.drawOn(c, x, top-height)
    return top-height


def page(num, label):
    c.setFillColor(PAPER)
    c.rect(0, 0, W, H, fill=1, stroke=0)
    text(M, H-45, 'PRODUCE A VALUE', 10, True)
    text(RIGHT-stringWidth(label, 'Helvetica-Bold', 7), H-43, label, 7, True)
    c.setStrokeColor(INK)
    c.setLineWidth(1.5)
    c.line(M, H-53, RIGHT, H-53)
    c.line(M, 41, RIGHT, 41)
    text(M, 28, 'produceavalue.com', 7, True)
    text(260, 28, 'PRIVATE PROPOSAL - ONE HUNDRED / CRO TRAIL', 6.7, True)
    text(RIGHT-29, 28, f'{num:02d} / 04', 7, True)


def label(y, value):
    w = stringWidth(value, 'Helvetica-Bold', 8)+15
    c.setFillColor(YELLOW)
    c.rect(M+2, y-13, w, 20, fill=1, stroke=0)
    c.setStrokeColor(INK)
    c.rect(M, y-11, w, 20, fill=0, stroke=1)
    text(M+8, y-4, value, 8, True)


def heading(y, value, size=13):
    text(M, y, value.upper(), size, True)
    return y-15


def rule(y):
    c.setStrokeColor(INK)
    c.setLineWidth(1.2)
    c.line(M, y, RIGHT, y)
    return y


# Page 1 - overview
page(1, 'COMMERCIAL PROPOSAL / 23 OCTOBER 2026')
label(H-83, 'ONE HUNDRED / CRO TRAIL 2027')
text(M, H-128, 'ONE REGISTRATION PLATFORM.', 28, True)
text(M, H-159, 'EVERY EVENT IN THE GROUP.', 28, True, ORANGE)
y = para(M, H-184, '<b>A bilingual CRO Trail website and a modular ticketing platform that the One Hundred team can reuse for its events worldwide.</b>', style=ParagraphStyle('lead', parent=BODY, fontSize=11, leading=15)) - 17
box_w = (RIGHT-M-14)/3
for i, (title, amount, sub, bg) in enumerate([
    ('BUILD', 'EUR 6,200', 'one time', INK),
    ('MAINTENANCE', 'EUR 1,350', 'per year', ORANGE),
    ('DELIVERY', '45', 'working days guaranteed', VIOLET),
]):
    x = M+i*(box_w+7)
    c.setFillColor(bg); c.rect(x, y-69, box_w, 69, fill=1, stroke=0)
    fg = PAPER if i == 0 else INK
    text(x+11, y-17, title, 8, True, fg)
    text(x+11, y-41, amount, 18, True, fg)
    text(x+11, y-58, sub, 8, True, fg)
y -= 95
y = heading(y, 'What the proposal delivers', 14)-4
items = [
('01', '<b>CRO Trail website.</b> Responsive Italian and English pages for the 100, 42 and 21 km races, with direct registration paths and final event content supplied or approved by the Client.'),
('02', '<b>Shared ticketing platform.</b> One reusable system for all One Hundred group events. The protected admin panel lets the team create events, add as many races as needed and manage entry settings without commissioning a new build for each race.'),
('03', '<b>Event-specific appearance.</b> Admin selectors control the registration interface\'s colours and typography for each race, so the shared platform can reflect its event identity.'),
('04', '<b>Unified payments and invoicing.</b> The same Dubai operating company invoices registrations for every event and owns the single Stripe and PayPal accounts used across the group. Every order remains identifiable by event and race.'),
]
rule(y)
for idx, content in items:
    text(M+2, y-17, idx, 9, True, ORANGE)
    bottom = para(M+36, y-9, content, RIGHT-M-40)
    y = bottom-12
    rule(y)
y -= 18
c.setFillColor(YELLOW); c.rect(M+4, y-46, RIGHT-M-4, 46, fill=1, stroke=0)
c.setStrokeColor(INK); c.rect(M, y-42, RIGHT-M-4, 46, fill=0, stroke=1)
para(M+12, y-5, '<b>The price covers the common platform and self-service event creation. Adding another event or race through the admin panel carries no separate development fee.</b>', RIGHT-M-28)
c.showPage()

# Page 2 - details
page(2, 'PLATFORM AND OPERATIONS')
label(H-83, 'SCOPE / OPTION B')
text(M, H-132, 'BUILT TO RUN MORE', 24, True)
text(M, H-160, 'THAN ONE RACE.', 24, True, ORANGE)
y = H-194
features = [
('ADMIN / Event control', 'Protected access to create races, set prices and entry rules, edit participant data, issue individual tickets and generate voucher codes without developer intervention.'),
('BRANDING / Race identity', 'Colour and type selectors in the admin panel generate a distinct registration look for each race within the shared system.'),
('ENTRY / Eligibility flow', 'IT / EN registration with event-specific rules. CRO Trail requires UISP/EPS/FIDAL/RunCard membership or a non-member supplement of approximately EUR 10, plus an athletics medical certificate reviewed manually by the Client before ticket activation.'),
('PAYMENTS / One merchant setup', 'Stripe and PayPal checkout on the Client\'s shared accounts, payment status verified server-side, orders tagged by event and race, and administrative exports.'),
('GROUPS / Bulk registrations', 'CSV import for group entries, including 200-400 participants per batch, with individual ticket codes and automated email delivery. One order can contain multiple named tickets.'),
('DELIVERY / Dynamic tickets', 'Editable participant records and individual HTML ticket views; bulk delivery avoids generating hundreds of PDFs at once. PDFs can be generated on demand if required. Includes production setup, testing and admin handover.'),
]
gap = 35
colw = (RIGHT-M-gap)/2
for i, (title, body) in enumerate(features):
    col = i%2; row = i//2
    x = M+col*(colw+gap); top = y-row*101
    c.setStrokeColor(INK); c.setLineWidth(1.2); c.line(x, top, x+colw, top)
    text(x, top-17, title.upper(), 10, True)
    para(x, top-25, body, colw, SMALL)
y -= 328
y = heading(y, 'Annual maintenance / EUR 1,350', 13)-2
y = para(M, y, 'Includes VPS hosting, routine software package and security updates, monitoring, backup checks, correction of reproducible defects, and personal technical assistance directly from Tommaso Giovannoni throughout the year (365 days, via an agreed channel). The first annual period starts at go-live; renewal is EUR 1,350 per year at unchanged scope.')-22
c.setStrokeColor(ORANGE); c.setLineWidth(2)
for x in [M, M+colw+gap]: c.line(x, y, x+colw, y)
text(M, y-17, 'CLIENT RESPONSIBILITIES', 10, True)
para(M, y-25, 'The Client provides and controls the Dubai operating company\'s Stripe and PayPal merchant accounts, approved event rules and content, branding assets, and the legally required participant and medical-data notices.', colw, SMALL)
x=M+colw+gap
text(x, y-17, 'DIRECT THIRD-PARTY COSTS', 10, True)
para(x, y-25, 'Payment-provider commissions, email-provider fees, domains and any charges imposed by third-party services are excluded. Currency and payment-method availability depend on the merchant accounts\' actual configuration.', colw, SMALL)
y -= 100
para(M, y, 'This offer covers registration pages for additional group events through the shared platform. Standalone marketing websites for those events are separate work if requested.', style=SMALL)
c.showPage()

# Page 3 - technical foundation and proof
page(3, 'TECHNICAL FOUNDATION AND EVIDENCE')
label(H-83, 'WHY THIS APPROACH')
text(M, H-132, 'PROVEN ARCHITECTURE.', 25, True)
text(M, H-160, 'ROOM TO GROW.', 25, True, ORANGE)
para(M, H-185, '<b>Laravel 12 and PHP for business rules, Blade with HTML/CSS/JavaScript for the interfaces, a relational database for registrations and a self-hosted VPS for deployment. The platform does not depend on WordPress plugin chains or Webflow SaaS feature limits.</b>', style=ParagraphStyle('evidence-lead', parent=BODY, fontSize=10, leading=14))
top = H-264
c.setFillColor(INK); c.rect(M, top-189, RIGHT-M, 189, fill=1, stroke=0)
c.setFillColor(ORANGE); c.rect(M, top-189, 5, 189, fill=1, stroke=0)
text(M+15, top-20, 'RECENT REFERENCE / FRANCIGENA TUSCANY MARATHON', 8, True, YELLOW)
text(M+15, top-53, '1,500+ REGISTRATIONS.', 21, True, PAPER)
text(M+15, top-77, 'PEAK SERVER USE BELOW 6%.', 18, True, PAPER)
para(M+15, top-90, 'The same underlying architecture handled the 27 September 2026 event. Average per-ticket fees fell from EUR 3.15 with the previous provider to EUR 0.67 with the custom implementation, representing more than EUR 3,700 in annual commission savings at roughly 1,500 entries.', RIGHT-M-30, WHITE_SMALL)
para(M+15, top-147, 'The official event site (francigenatuscanymarathon.com) reports 1,502 registrations. Server and fee figures are Produce a Value project operating data. Outcomes for One Hundred depend on its own volume and payment mix.', RIGHT-M-30, WHITE_SMALL)
y = top-223
y = heading(y, 'Available extensions on request', 13)-4
extras=[
('01','REDIS / VALKEY','Cache and queue capacity as operational needs grow.'),
('02','QR VALIDATION','Scannable tickets and check-in controls.'),
('03','RACE RESULTS','Result entry and published rankings.'),
('04','SEPA QR TRANSFER','Optional bank-transfer payment path.'),
]
colgap=30; colw=(RIGHT-M-colgap)/2
for i,(idx,title,body) in enumerate(extras):
    col=i%2; row=i//2; x=M+col*(colw+colgap); t=y-row*76
    c.setStrokeColor(INK); c.setLineWidth(1.3); c.line(x,t,x+colw,t)
    text(x,t-17,idx,8,True,ORANGE)
    text(x+27,t-17,title,9,True)
    para(x+27,t-26,body,colw-27,SMALL)
para(M,y-175,'These extensions are not included in the EUR 6,200 build price and would be specified and quoted separately. The shared multi-event admin, per-race visual selectors and unified Stripe/PayPal setup are included in the base scope.',style=SMALL)
c.showPage()

# Page 4 - financial and guarantee
page(4, 'PRICE, DELIVERY AND GUARANTEE')
label(H-83, 'COMMERCIAL CONDITIONS')
text(M, H-132, 'A FIXED PRICE.', 25, True)
text(M, H-160, 'AN ACCOUNTABLE DEADLINE.', 25, True, ORANGE)
y=H-195
rule(y)
for name, amount, bg in [
    ('One-time platform build', 'EUR 6,200', None),
    ('Annual maintenance, including VPS', 'EUR 1,350 / year', None),
    ('First-year total, if delivered on time', 'EUR 7,550', YELLOW),
]:
    if bg: c.setFillColor(bg); c.rect(M,y-36,RIGHT-M,36,fill=1,stroke=0)
    text(M+6,y-23,name,9,True)
    w=stringWidth(amount,'Helvetica-Bold',15)
    text(RIGHT-w-6,y-25,amount,15,True)
    y-=36; rule(y)
y-=28
y=heading(y,'Payment schedule',13)-4
colw=(RIGHT-M-18)/2
for i,(tag,amount,body) in enumerate([
    ('01 / START','EUR 3,100','50% of the build fee, due in advance. Collaboration begins on the date the deposit is credited to the bank account stated in the contract.'),
    ('02 / BEFORE DEPLOYMENT','EUR 4,450','EUR 3,100 build balance plus the first EUR 1,350 annual maintenance fee. Maintenance is paid 100% in advance on the same day as the build balance.'),
]):
    x=M+i*(colw+18)
    c.setFillColor(colors.HexColor('#eee5d7')); c.setStrokeColor(INK)
    c.rect(x,y-91,colw,91,fill=1,stroke=1)
    text(x+10,y-17,tag,8,True,ORANGE)
    text(x+10,y-41,amount,17,True)
    para(x+10,y-49,body,colw-20,SMALL)
y-=120
y=heading(y,'45-working-day delivery guarantee',13)-5
y=para(M,y,'The complete, tested product will be delivered ready for deployment within <b>45 working days</b> from the collaboration start date. Working days are Monday through Friday; Saturdays and Sundays do not count. The first counted day is the deposit-credit date if it falls on a Monday-Friday, otherwise the next Monday-Friday.')-17
h=68
c.setFillColor(INK); c.rect(M,y-h,RIGHT-M,h,fill=1,stroke=0)
c.setFillColor(ORANGE); c.rect(M,y-h,5,h,fill=1,stroke=0)
para(M+13,y-10,'If delivery exceeds the 45-working-day deadline, the <b>EUR 3,100 build balance is waived in full</b>. Only the EUR 1,350 annual maintenance fee remains payable before deployment; the EUR 3,100 advance already paid remains part of the build price. The resulting first-year total is EUR 4,450. Maintenance starts at go-live and is renewed annually in advance.',RIGHT-M-26,WHITE)
y-=h+22
for i,(title,body) in enumerate([
    ('DELIVERY STANDARD','"Delivered" means the agreed CRO Trail site and shared ticketing platform are functionally complete, tested and available for the Client to inspect in a production-ready environment, with admin access and handover documentation.'),
    ('COMMERCIAL BASIS','Prices are in euro and exclude VAT or equivalent taxes where applicable. The Client\'s Dubai operating entity will contract for the service and invoice event registrations; its legal details will be stated in the contract.'),
]):
    x=M+i*(colw+18)
    c.setStrokeColor(ORANGE);c.setLineWidth(2);c.line(x,y,x+colw,y)
    text(x,y-16,title,9,True)
    para(x,y-23,body,colw,SMALL)
y-=87
para(M,y,'This commercial proposal is to be incorporated into the written contract. The fixed scope and guarantee above are not conditioned on adding or purchasing other group-event websites.',style=SMALL)
c.save()
print(OUT)
