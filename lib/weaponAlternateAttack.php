<?php
// This file has been automatically generated.
// You may edit it, but be aware that if the source
// file that was used to generate it changes so will
// this file. Possibly overwriting your changes.

function isAlternateAttack($job, $sex, $weapon) {
    if ($job > 4000) {
        $job = $job - 3950;
    }
    switch ($job) {
        case 25:
        case 261:
        case 262:
        case 272:
        case 273:
        case 274:
        case 354:
        case 355:
            return $weapon == 22;
        case 24:
        case 265:
        case 278:
        case 279:
        case 356:
            switch($weapon) {
                case 18:
                case 19:
                case 20:
                case 21:
                    return true;
                default:
                    return false;
            }
            break;
        case 1:
        case 7:
        case 13:
        case 14:
        case 21:
        case 52:
        case 58:
        case 64:
        case 65:
        case 72:
        case 74:
        case 80:
        case 86:
        case 87:
        case 94:
        case 104:
        case 110:
        case 116:
        case 123:
        case 130:
        case 131:
        case 132:
        case 133:
        case 138:
        case 139:
        case 140:
        case 141:
        case 142:
        case 143:
        case 144:
        case 145:
        case 146:
        case 152:
        case 159:
        case 160:
        case 302:
        case 308:
        case 330:
        case 331:
            switch($weapon) {
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                    return true;
                default:
                    return false;
            }
            break;
        case 2:
        case 5:
        case 53:
        case 56:
        case 75:
        case 78:
            return $weapon == 1;
        case 3:
        case 54:
        case 76:
            return $weapon != 11;
        case 6:
        case 11:
        case 17:
        case 19:
        case 20:
        case 57:
        case 62:
        case 68:
        case 70:
        case 71:
        case 79:
        case 84:
        case 90:
        case 92:
        case 93:
        case 106:
        case 112:
        case 118:
        case 119:
        case 122:
        case 125:
        case 126:
        case 129:
        case 134:
        case 135:
        case 148:
        case 154:
        case 155:
        case 158:
        case 161:
        case 307:
        case 310:
        case 313:
        case 314:
        case 328:
            return $weapon == 11;
        case 8:
        case 59:
        case 81:
        case 107:
        case 113:
        case 149:
        case 306:
            return $weapon == 15;
        case 9:
        case 60:
        case 82:
        case 99:
        case 105:
        case 111:
        case 147:
        case 277:
        case 290:
        case 292:
        case 305:
        case 353:
            switch($weapon) {
                case 10:
                case 23:
                    return $sex == 0;
                case 1:
                    return $sex == 1;
                default:
                    return false;
            }
            break;
        case 10:
        case 18:
        case 61:
        case 69:
        case 83:
        case 91:
        case 108:
        case 114:
        case 121:
        case 128:
        case 136:
        case 137:
        case 150:
        case 157:
        case 162:
        case 303:
        case 309:
        case 329:
            switch($weapon) {
                case 2:
                case 6:
                case 7:
                case 8:
                    return true;
                default:
                    return false;
            }
            break;
        case 12:
        case 63:
        case 85:
        case 109:
        case 115:
        case 151:
        case 304:
            switch($weapon) {
                case 16:
                case 25:
                case 26:
                case 27:
                case 28:
                case 29:
                case 30:
                    return true;
                default:
                    return false;
            }
            break;
        case 15:
        case 66:
        case 88:
        case 120:
        case 127:
        case 156:
        case 312:
            switch($weapon) {
                case 0:
                case 12:
                    return true;
                default:
                    return false;
            }
            break;
        case 16:
        case 67:
        case 89:
        case 117:
        case 124:
        case 153:
        case 311:
            switch($weapon) {
                case 5:
                case 10:
                case 15:
                case 23:
                    return true;
                default:
                    return false;
            }
            break;
        case 0:
        case 23:
        case 51:
        case 73:
        case 95:
        case 240:
        case 241:
        case 357:
            switch($weapon) {
                case 1:
                    return true;
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                    return $sex == 1;
                default:
                    return false;
            }
            break;
        default:
            return false;
    }
}
?>

